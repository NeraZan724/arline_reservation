<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $phone_no = $_POST['phone_no'];
    $address = $_POST['address'];

    // Validate server-side
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }
    if (!is_numeric($phone_no) || strlen($phone_no) < 10) {
        die("Phone number must be numeric and at least 10 digits long.");
    }

    // Establish a database connection
    $conn = new mysqli("localhost", "root", "", "airline_reservation");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $email_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($email_check_query);

    if ($result->num_rows > 0) {
        // Email already exists
        die("Email is already registered. Please use a different email.");
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $query = "INSERT INTO users (username, password, email, name, phone_no, address) 
                  VALUES ('$username', '$hashed_password', '$email', '$name', '$phone_no', '$address')";

        if ($conn->query($query) === TRUE) {
            echo "New record created successfully";
            // Redirect to login or other page
            // header("Location: login_page.php");
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>
