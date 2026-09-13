<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT * FROM issues WHERE user_id = ? ORDER BY created_at DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$issues = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard - CivicConnect</title>
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


<div class="dashboard">

    <h1>Welcome, <?php echo $_SESSION["user_name"]; ?> 👋</h1>

    <a href="report_issue.php" class="btn">
        + Report New Issue
    </a>

    <h2>Your Reported Issues</h2>


    <?php

    if ($issues->num_rows > 0) {

        while ($row = $issues->fetch_assoc()) {

            echo "<div class='issue-card'>";

            echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";

            echo "<p>" . htmlspecialchars($row["description"]) . "</p>";

            echo "<p><b>Category:</b> " . htmlspecialchars($row["category"]) . "</p>";

            echo "<p><b>Status:</b> " . htmlspecialchars($row["status"]) . "</p>";

            echo "</div>";
        }

    } else {

        echo "<p>No issues reported yet.</p>";

    }

    ?>

</div>

</body>
</html>