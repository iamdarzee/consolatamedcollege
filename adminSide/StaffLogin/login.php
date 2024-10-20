<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sister Leonella Consolata Medical College</title>
    <meta name="title" content="Sister Leonella Consolata Medical College">
    <meta name="author" content="Darzee">
    <meta name="description" content="The Official site of Sister Leonella Consolata Medical College">
    <link rel="shortcut icon" href="../../images/lock.jpg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black flex justify-center items-center h-screen">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <a class="nav-link" href="../../index.php">
            <h1 class="text-center text-4xl font-cursive text-chartreuse underline mb-6">Sister Leonella Consolata Medical College</h1>
        </a>
        <?php 
        if(!empty($login_err)){
            echo "<div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\">$login_err</div>";
        }        
        ?>
        <form action="login_process.php" method="post">
            <div class="mb-4">
                <label for="staff_id" class="block text-gray-700 font-bold mb-2">Staff ID</label>
                <input type="number" id="staff_id" name="staff_id" placeholder="Enter Staff ID" required
                    class="px-4 py-2 w-full border rounded-md focus:outline-none focus:border-blue-500 <?php echo (!empty($staff_id_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $staff_id; ?>">
                <?php if(!empty($staff_id_err)) echo '<span class="text-red-500 text-sm">'.$staff_id_err.'</span>'; ?>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required
                    class="px-4 py-2 w-full border rounded-md focus:outline-none focus:border-blue-500 <?php echo (!empty($password_err)) ? 'border-red-500' : ''; ?>">
                <?php if(!empty($password_err)) echo "<span class=\"text-red-500 text-sm\">$password_err</span>"; ?>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Login
            </button>
        </form>
    </div>
</body>
</html>
