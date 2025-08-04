<?php
include "config.php";
session_start();

$message = "";
$remaining_time = 0;
$attempts = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $attempts = $user['failed_attempts'];
        $last_failed = strtotime($user['last_failed_login']);

        if ($attempts >= 3 && (time() - $last_failed) > 300) {
            $conn->query("UPDATE users SET failed_attempts=0 WHERE username='$username'");
            $attempts = 0;
        }

        $user = $conn->query("SELECT * FROM users WHERE username='$username'")->fetch_assoc();
        $attempts = $user['failed_attempts'];
        $last_failed = strtotime($user['last_failed_login']);

        if ($attempts >= 3 && (time() - $last_failed) <= 300) {
            $remaining_time = ($last_failed + 300) - time();
            $minutes = floor($remaining_time / 60);
            $seconds = $remaining_time % 60;
            $message = "Account blocked for $minutes min $seconds sec.";
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

                $user = $conn->query("SELECT * FROM users WHERE username='$username'")->fetch_assoc();
                $attempts = $user['failed_attempts'];

                if ($attempts >= 3) {
                    $remaining_time = 300;
                    $message = "Account is now blocked for 5 minutes.";
                } else {
                    $message = "Invalid password. Attempts: $attempts/3";
                }
            }
        }
    } else {
        $message = "Invalid username.";
    }
} else {
    $query = "SELECT * FROM users WHERE username='admin'";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $attempts = $user['failed_attempts'];
        $last_failed = strtotime($user['last_failed_login']);

        if ($attempts >= 3 && (time() - $last_failed) <= 300) {
            $remaining_time = ($last_failed + 300) - time();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - School Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-4">School Management Login</h2>

        <?php if ($message): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if ($attempts > 0 && $attempts < 3): ?>
            <p class="text-yellow-600 text-center mb-2">
                ⚠ You have used <?= $attempts ?>/3 attempts.
            </p>
        <?php endif; ?>

        <?php if ($remaining_time > 0): ?>
            <p class="text-red-600 text-center mb-4">
                Try again in <span id="countdown"><?= $remaining_time ?></span> seconds
            </p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Username</label>
                <input type="text" name="username" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                       <?= $remaining_time > 0 ? 'disabled' : '' ?>>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                       <?= $remaining_time > 0 ? 'disabled' : '' ?>>
            </div>

            <button type="submit"
                <?= $remaining_time > 0 
                    ? 'disabled class="w-full bg-gray-400 text-white font-bold py-2 rounded-lg cursor-not-allowed"' 
                    : 'class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition duration-200"' ?>>
                <?= $remaining_time > 0 ? 'Locked' : 'Login' ?>
            </button>
        </form>
    </div>

    <?php if ($remaining_time > 0): ?>
    <script>
        let timeLeft = <?= $remaining_time ?>;
        const countdown = document.getElementById('countdown');
        const interval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(interval);
                location.reload();  
            } else {
                timeLeft--;
                countdown.innerText = timeLeft;
            }
        }, 1000);
    </script>
    <?php endif; ?>

</body>
</html>
