<?php
require_once __DIR__ . "/config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Get all issues of the logged-in user
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT * FROM issues WHERE user_id = ? ORDER BY created_at DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$issues = $stmt->get_result();


// Update status
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $issue_id = $_POST["issue_id"];
    $status = $_POST["status"];

    $update = $conn->prepare(
        "UPDATE issues SET status = ? WHERE issue_id = ? AND user_id = ?"
    );

    $update->bind_param("sii", $status, $issue_id, $user_id);

    if ($update->execute()) {
        $message = "Issue status updated successfully!";
    } else {
        $message = "Error updating status!";
    }

    header("Location: update_status.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Status - CivicConnect</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav>
    <h2>CivicConnect</h2>

    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="report_issue.php">Report Issue</a>
        <a href="update_status.php">Update Status</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="dashboard">

    <h1>Update Issue Status</h1>

    <?php
    if ($issues->num_rows > 0) {
        while ($row = $issues->fetch_assoc()) {
    ?>

        <div class="issue-card">

            <h3><?php echo htmlspecialchars($row["title"]); ?></h3>

            <p>
                <b>Current Status:</b>
                <?php echo htmlspecialchars($row["status"]); ?>
            </p>

            <form method="POST">

                <input type="hidden"
                       name="issue_id"
                       value="<?php echo $row["issue_id"]; ?>">

                <select name="status">

                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Resolved">Resolved</option>

                </select>

                <button type="submit">
                    Update Status
                </button>

            </form>

        </div>

    <?php
        }
    } else {
        echo "<p>No issues found.</p>";
    }
    ?>

</div>

</body>
</html>