<?php
include_once 'header.php';
include_once 'connection.php';
?>

<?php

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["useruid"])) {
    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];

    $stmt = $dbh->prepare("SELECT users_pwd FROM users WHERE users_name = ?");
    $stmt->execute([$_SESSION['username']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($oldPassword, $userData['users_pwd'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $dbh->prepare("UPDATE users SET users_pwd = ? WHERE users_name = ?");
        $stmt->execute([$hashedPassword, $_SESSION['username']]);

        header("Location: changepassword.php?error=none");
        exit;
    } else {
        $error = "Incorrect old password.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["employeeuid"])) {
    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];

    $stmt = $dbh->prepare("SELECT employees_pwd FROM employee WHERE employees_name = ?");
    $stmt->execute([$_SESSION['employeename']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($oldPassword, $userData['employees_pwd'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $dbh->prepare("UPDATE employee SET employees_pwd = ? WHERE employees_name = ?");
        $stmt->execute([$hashedPassword, $_SESSION['employeename']]);

        header("Location: changepassword.php?error=none");
        exit;
    } else {
        $error = "Incorrect old password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>

<body class="changepassword">
    <h1>Change Password</h1>
    <?php if (isset($error)) : ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password" required><br><br>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required><br><br>
        <input type="submit" value="Change Password">
    </form>
    <?php
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "none") {
            echo "<p>You have changed your password!</p>";
        }
    }
    ?>
</body>

</html>


<?php
include_once 'footer.php';
?>