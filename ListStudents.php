<?php
include "functions.php";
checkLogin();
include "config.php";

$result = $conn->query("SELECT * FROM students");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-3xl font-bold text-blue-600 mb-4 text-center"> Student List</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">ID</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Name</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Reg No</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Age</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Email</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Phone</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Course</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= $row['id'] ?></td>
                            <td class="px-4 py-2"><?= $row['name'] ?></td>
                            <td class="px-4 py-2"><?= $row['reg_no'] ?></td>
                            <td class="px-4 py-2"><?= $row['age'] ?></td>
                            <td class="px-4 py-2"><?= $row['email'] ?></td>
                            <td class="px-4 py-2"><?= $row['phone'] ?></td>
                            <td class="px-4 py-2"><?= $row['course'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-6">
            <a href="dashboard.php" class="text-blue-500 hover:underline">⬅ Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
