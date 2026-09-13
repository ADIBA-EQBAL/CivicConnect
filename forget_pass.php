<?php
include __DIR__ . "/config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $new_password = $_POST["password"];

    // First check if email exists
    $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $update = $conn->prepare(
            "UPDATE user SET password = ? WHERE email = ?"
        );

        $update->bind_param("ss", $hashed_password, $email);

        if ($update->execute()) {
            $message = "Password updated successfully! Please login.";
        } else {
            $message = "Something went wrong!";
        }

    } else {
        $message = "Email not found!";
    }
}
?>