<?php
include "functions.php";
checkLogin();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $reg_no = sanitize($_POST['reg_no']);
    $age = intval($_POST['age']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $course = sanitize($_POST['course']);

   
    if (!preg_match("/^[a-zA-Z ]{2,}$/", $name)) {
        $message = "Name must have at least 2 letters and only alphabets.";
    } elseif (!preg_match("/^REG-[0-9]{4}-[0-9]{4}$/", $reg_no)) {
        $message = "Invalid registration format (REG-YYYY-NNNN).";
    } elseif ($age < 18 || $age > 25) {
        $message = "Age must be between 18 and 25.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!empty($phone) && !preg_match("/^[0-9]{10}$/", $phone)) {
        $message = "Phone must be 10 digits if provided.";
    } else {
      
        $check_reg = $conn->query("SELECT id FROM students WHERE reg_no='$reg_no'");
        if ($check_reg->num_rows > 0) {
            $message = " This registration number already exists. Please use another one.";
        } else {
            $sql = "INSERT INTO students (name, reg_no, age, email, phone, course) 
                    VALUES ('$name','$reg_no','$age','$email','$phone','$course')";
            if ($conn->query($sql)) {
                $message = "Student Registered Successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-4">Register Student</h2>

        <?php if ($message): ?>
            <div class="mb-4 px-4 py-2 rounded <?= strpos($message, 'Successfully') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Name</label>
                <input type="text" name="name" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Registration No</label>
                <input type="text" name="reg_no" placeholder="REG-2024-0001" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Age</label>
                <input type="number" name="age" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Phone</label>
                <input type="text" name="phone"  
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Course</label>
                <select name="course" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="BSc">BSc</option>
                    <option value="BCom">BCom</option>
                    <option value="BA">BA</option>
                </select>
            </div>

            <button type="submit" 
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg transition duration-200">
                Register Student
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="dashboard.php" class="text-blue-500 hover:underline">⬅ Back to Dashboard</a>
        </div>
    </div>
</body>
</html>