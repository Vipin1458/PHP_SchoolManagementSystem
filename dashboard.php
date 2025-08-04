<?php
include "functions.php";
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - School Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">

    <div class="w-full max-w-lg bg-white rounded-lg shadow-md p-8 text-center">
        
        <h2 class="text-3xl font-bold text-blue-600 mb-4">
             Welcome, <?php echo $_SESSION['username']; ?>!
        </h2>

        <p class="text-gray-600 mb-6">Choose an option below to manage the school system.</p>

        <div class="space-y-4">
            <a href="AddStudents.php" 
               class="block w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg shadow transition duration-200">
                Register Student
            </a>

            <a href="ListStudents.php" 
               class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg shadow transition duration-200">
                View Student List
            </a>

            <a href="logout.php" 
               class="block w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg shadow transition duration-200">
                Logout
            </a>
        </div>
    </div>

</body>
</html>