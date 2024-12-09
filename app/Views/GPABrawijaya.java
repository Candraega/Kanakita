import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Scanner;

class Student {
    String name;
    double gpa;
    String timestamp; // Waktu input

    Student(String name, double gpa, String timestamp) {
        this.name = name;
        this.gpa = gpa;
        this.timestamp = timestamp;
    }
}

public class GPABrawijaya {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        ArrayList<Student> students = new ArrayList<>();

        System.out.println("===========================================================================================");
        System.out.println("Hello, I'm PoliPoli from T3A! Welcome to PoliPoli GPA Vocational Brawijaya University :)");
        System.out.println("===========================================================================================");

        int choice;

        do {
            System.out.println("\nMain Menu:");
            System.out.println("1. Input Student Name and GPA");
            System.out.println("2. Display Students Sorted by GPA (Highest to Lowest)");
            System.out.println("3. Search Student (by Name/Prefix or by GPA)");
            System.out.println("4. Exit");
            System.out.print("Choose Option (1/2/3/4): ");
            choice = scanner.nextInt();
            scanner.nextLine(); // Consume newline character

            switch (choice) {
                case 1:
                    System.out.print("Enter number of students to add: ");
                    int n = scanner.nextInt();
                    scanner.nextLine(); // Consume newline character

                    for (int i = 0; i < n; i++) {
                        System.out.print("Enter student " + (i + 1) + " name: ");
                        String name = scanner.nextLine();
                        System.out.print("Enter student " + (i + 1) + " GPA: ");
                        double gpa = scanner.nextDouble();
                        scanner.nextLine(); // Consume newline character

                        // Mendapatkan waktu saat ini
                        String timestamp = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date());
                        students.add(new Student(name, gpa, timestamp));
                    }

                    // Display all students and total count
                    System.out.println("\nList of all students:");
                    int index = 1;
                    for (Student student : students) {
                        System.out.println(index++ + ". " + student.name + " - " + student.gpa + " (Added on: " + student.timestamp + ")");
                    }
                    System.out.println("Total number of students: " + students.size());
                    break;

                case 2:
                    if (students.isEmpty()) {
                        System.out.println("No data available. Please add students first.");
                        break;
                    }

                    // Implementing Bubble Sort (Sorting by GPA, Highest to Lowest)
                    bubbleSort(students);

                    System.out.println("\nStudents sorted by GPA (Highest to Lowest):");
                    index = 1;
                    for (Student student : students) {
                        System.out.println(index++ + ". " + student.name + " - " + student.gpa + " (Added on: " + student.timestamp + ")");
                    }
                    break;

                case 3:
                    if (students.isEmpty()) {
                        System.out.println("No data available. Please add students first.");
                        break;
                    }

                    System.out.println("Search Options:");
                    System.out.println("1. Search by Name or Prefix");
                    System.out.println("2. Search by GPA");
                    System.out.print("Choose Option (1/2): ");
                    int searchOption = scanner.nextInt();
                    scanner.nextLine(); // Consume newline character

                    switch (searchOption) {
                        case 1: // Search by Name or Prefix
                            System.out.print("Enter student name or prefix to search: ");
                            String searchQuery = scanner.nextLine();
                            boolean nameFound = false;

                            index = 1;
                            for (Student student : students) {
                                if (student.name.equalsIgnoreCase(searchQuery) || student.name.toLowerCase().startsWith(searchQuery.toLowerCase())) {
                                    System.out.println(index + ". " + student.name + " - " + student.gpa + " (Added on: " + student.timestamp + ")");
                                    nameFound = true;
                                }
                                index++;
                            }

                            if (!nameFound) {
                                System.out.println("No students found with name or prefix '" + searchQuery + "'");
                            }
                            break;

                        case 2: // Search by GPA
                            System.out.print("Enter GPA to search: ");
                            double searchGpa = scanner.nextDouble();
                            boolean gpaFound = false;

                            index = 1;
                            for (Student student : students) {
                                if (student.gpa == searchGpa) {
                                    System.out.println(index + ". " + student.name + " - " + student.gpa + " (Added on: " + student.timestamp + ")");
                                    gpaFound = true;
                                }
                                index++;
                            }

                            if (!gpaFound) {
                                System.out.println("No students found with GPA " + searchGpa);
                            }
                            break;

                        default:
                            System.out.println("Invalid search option. Please choose 1 or 2.");
                            break;
                    }
                    break;

                case 4:
                    System.out.println("Exiting program. Thank you!");
                    break;

                default:
                    System.out.println("Invalid choice. Please choose between 1-4.");
            }
        } while (choice != 4);

        scanner.close();
    }

    // Bubble Sort implementation to sort students by GPA (Descending)
    public static void bubbleSort(ArrayList<Student> students) {
        int n = students.size();
        for (int i = 0; i < n - 1; i++) {
            for (int j = 0; j < n - 1 - i; j++) {
                // Compare GPA of adjacent students and swap if needed
                if (students.get(j).gpa < students.get(j + 1).gpa) {
                    // Swap students[j] and students[j + 1]
                    Student temp = students.get(j);
                    students.set(j, students.get(j + 1));
                    students.set(j + 1, temp);
                }
            }
        }
    }
}