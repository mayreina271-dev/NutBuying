<?php
session_start();
require_once 'config/db.php';

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("
        SELECT id, fullname, username, password, role, status
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // DEBUG (REMOVE LATER)
        // var_dump($user); exit;

        if ($user['status'] !== 'Active') {
            $error = "Account inactive";
        }
        else if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid Password";
        }

    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/login.css">
<title>Login</title>
</head>
<body>

<div class="login-box">
    <h2>NutBuying System</h2>

    <?php if($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>
</div>

</body>
</html>