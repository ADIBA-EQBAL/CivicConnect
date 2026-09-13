<?php
include "config.php";

$message = "";

if (isset($_POST["register"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Email already registered!";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO user (name, email, phone, password) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $name, $email, $phone, $password);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            $message = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - CivicConnect</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav>
    <h2>CivicConnect</h2>

    <div>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</nav>

<div class="form-container">

    <h2>Create Account</h2>

    <?php
    if ($message != "") {
        echo "<p class='message'>$message</p>";
    }
    ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="text" name="phone" placeholder="Phone Number" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>