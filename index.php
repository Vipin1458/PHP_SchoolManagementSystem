<?php
include "config.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($user['failed_attempts'] >= 3 && strtotime($user['last_failed_login']) > strtotime('-5 minutes')) {
            $message = "🚨 Account blocked for 5 minutes due to multiple failed attempts.";
        } else {
            if ($user['password'] == $password) {
                $_SESSION['username'] = $username;

                $conn->query("UPDATE users SET failed_attempts=0 WHERE username='$username'");
                header("Location: dashboard.php");
                exit();
            } else {
                $conn->query("UPDATE users 
                              SET failed_attempts = failed_attempts + 1, last_failed_login = NOW() 
                              WHERE username='$username'");
                $message = "❌ Invalid password.";
            }
        }
    } else {
        $message = "❌ Invalid username.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - School Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-4">School Management Login</h2>

        <?php if ($message): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Username</label>
                <input type="text" name="username" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition duration-200">
                Login
            </button>
        </form>
    </div>

</body>
</html>
