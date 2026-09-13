<?php
require_once __DIR__ . "/config.php";

if (!isset($conn)) {
    die("config.php loaded, but database connection was not created.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check whether the email exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");

    if ($stmt) {

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            // Verify hashed password
            if (password_verify($password, $user["password"])) {

                // Store user information in session
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];

                // Go to dashboard
                header("Location: dashboard.php");
                exit();

            } else {

                $error = "Incorrect password!";
            }

        } else {

            $error = "Email not found!";
        }

    } else {

        $error = "Database error!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - CivicConnect</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav>
    <h2>CivicConnect</h2>

    <div>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </div>
</nav>

<div class="form-container">

    <h2>Login</h2>

    <?php
    if ($error != "") {
        echo "<p style='color:red; text-align:center; margin-bottom:15px;'>$error</p>";
    }
    ?>

    <<form method="POST" action="login.php">>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        <button type="submit">Login</button>

    </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="forget_pass.php">Forgot Password?</a>
    </p>

    <p style="text-align:center; margin-top:10px;">
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>