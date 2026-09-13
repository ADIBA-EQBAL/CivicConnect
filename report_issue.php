<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST["report"])) {

    $user_id = $_SESSION["user_id"];

    $title = $_POST["title"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $location = $_POST["location"];

    $status = "Pending";

    $stmt = $conn->prepare(
        "INSERT INTO issues 
        (user_id, title, category, description, location, status)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "isssss",
        $user_id,
        $title,
        $category,
        $description,
        $location,
        $status
    );

    if ($stmt->execute()) {
        $message = "Issue reported successfully!";
    } else {
        $message = "Error reporting issue!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Report Issue - CivicConnect</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

<nav>

    <h2>CivicConnect</h2>

    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="report_issue.php">Report Issue</a>
        <a href="logout.php">Logout</a>
    </div>

</nav>


<div class="form-container">

    <h2>Report a Civic Issue</h2>

    <?php
    if ($message != "") {
        echo "<p class='message'>$message</p>";
    }
    ?>


    <form method="POST">

        <input
            type="text"
            name="title"
            placeholder="Issue Title"
            required
        >


        <select name="category" required>

            <option value="">Select Category</option>

            <option value="Road Damage">Road Damage</option>

            <option value="Garbage">Garbage</option>

            <option value="Water Problem">Water Problem</option>

            <option value="Street Light">Street Light</option>

            <option value="Other">Other</option>

        </select>


        <input
            type="text"
            name="location"
            placeholder="Location"
            required
        >


        <textarea
            name="description"
            placeholder="Describe the problem"
            required
        ></textarea>


        <button type="submit" name="report">
            Submit Issue
        </button>

    </form>

</div>

</body>
</html>