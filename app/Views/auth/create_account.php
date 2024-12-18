<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Word</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional, for styling -->
</head>
<body>
    <div class="container">
        <h1>Create New Word</h1>
        <form id="createWordForm">
            <label for="japanese">Japanese Word:</label>
            <input type="text" id="japanese" name="japanese" required><br><br>

            <label for="romaji">Romaji:</label>
            <input type="text" id="romaji" name="romaji" required><br><br>

            <label for="meaning">Meaning:</label>
            <textarea id="meaning" name="meaning" rows="4" required></textarea><br><br>

            <button type="submit">Create Word</button>
        </form>

        <div id="responseMessage"></div>
    </div>

    <script>
        document.getElementById('createWordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect form data
            const formData = new FormData(this);
            const data = {
                japanese: formData.get('japanese'),
                romaji: formData.get('romaji'),
                meaning: formData.get('meaning'),
            };

            // Send the data to the backend API using Fetch API
            fetch('http://192.168.1.7:8000/api/v1/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(responseData => {
                const responseMessage = document.getElementById('responseMessage');
                if (responseData.status === 'success') {
                    responseMessage.innerHTML = `<p style="color: green;">Word successfully created!</p>`;
                } else if (responseData.errors) {
                    let errorsHtml = '<ul>';
                    for (const error in responseData.errors) {
                        errorsHtml += `<li style="color: red;">${responseData.errors[error]}</li>`;
                    }
                    errorsHtml += '</ul>';
                    responseMessage.innerHTML = errorsHtml;
                } else {
                    responseMessage.innerHTML = `<p style="color: red;">An error occurred.</p>`;
                }
            })
            .catch(error => {
                document.getElementById('responseMessage').innerHTML = `<p style="color: red;">An error occurred: ${error}</p>`;
            });
        });
    </script>
</body>
</html>
