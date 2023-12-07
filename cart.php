<?php
$servername = "localhost";
$username = "root";
$password = " ";
$dbname = "shopping_cart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a table if it doesn't exist
$tableName = "users";
$sql = "CREATE TABLE IF NOT EXISTS $tableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Define variables to store user input
$username = "";
$password = "";
$errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = cleanInput($_POST["username"]);
    $password = cleanInput($_POST["password"]);

    // Validate username and password (for example, simple non-empty checks)
    if (empty($username) || empty($password)) {
        $errorMessage = "Username and password are required";
    } else {
        // Check user credentials against the database
        $selectSql = "SELECT * FROM $tableName WHERE username = '$username'";
        $result = $conn->query($selectSql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the password using password_verify (for real-world scenarios)
            if (password_verify($password, $row["password"])) {
                $errorMessage = "Login successful!";
            } else {
                $errorMessage = "Invalid password";
            }
        } else {
            $errorMessage = "User not found";
        }
    }
}

// Function to sanitize user input
function cleanInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Close the database connection
$conn->close();
?>


