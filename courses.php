<?php
session_start();
require_once "./config.php";


// Check if the student is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}


// Initialize variables for pagination
$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Initialize search variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = '';

if (!empty($search)) {
    $where_clause = "WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
}

// Get total number of courses for pagination
$count_query = "SELECT COUNT(*) as total FROM courses $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_courses = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_courses / $items_per_page);

// Fetch courses with pagination and search
$query = "SELECT * FROM courses $where_clause LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sister Leonella Consolata Medical College Offered Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="./media/logo.jpg" type="image/x-icon">
    <style>
        .course-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-600">
    <div class="container mx-auto px-4 py-8">
        <!-- Search Bar -->
        <div class="mb-8">
            <form action="" method="GET" class="flex justify-center">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                    placeholder="Search courses..." 
                    class="px-4 py-2 w-full max-w-md rounded-l-lg border-2 border-blue-500 focus:outline-none">
                <button type="submit" 
                    class="bg-blue-500 text-white px-6 py-2 rounded-r-lg hover:bg-blue-600">
                    Search
                </button>
            </form>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($course = mysqli_fetch_assoc($result)): ?>
                <div class="course-card bg-black rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-blue-600 mb-2"><?php echo htmlspecialchars($course['name']); ?></h3>
                        <p class="mb-2">
                            <span style="color: red; font-weight: bold;">Category:</span><br> 
                            <span style="color: white;"><?php echo htmlspecialchars($course['category']); ?></span>
                        </p>
                        <p class="mb-2">
                            <span style="color: orange; font-weight: bold;">Requirements:</span><br> 
                            <span style="color: white;"><?php echo htmlspecialchars($course['requirements']); ?></span>
                        </p>

                        <p class="mb-2">
                            <span style="color: green; font-weight: bold;">Duration:</span> <br>
                            <span style="color: white;"><?php echo htmlspecialchars($course['duration']); ?></span>
                        </p>

                        <p class="mb-4">
                            <span style="color: blue; font-weight: bold;">Fee:</span><br> 
                            <span style="color: white;">(Total) KES <?php echo number_format($course['fee'], 2); ?></span>
                        </p>

                        
                        <div class="text-sm text-white mb-4">
                            <p class="underline">Intake Period:</p>
                            <p>Start: <?php echo htmlspecialchars($course['intake_start']); ?></p>
                            <p>End: <?php echo htmlspecialchars($course['intake_end']); ?></p>
                        </div>

                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                            <button class="add-to-cart bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full"
                                data-course-id="<?php echo $course['id']; ?>">
                                Add to Application
                            </button>
                        <?php else: ?>
                            <a href="index.php" class="block text-center bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Login to Apply
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="flex justify-center mt-8">
                <div class="flex space-x-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"
                           class="px-4 py-2 border <?php echo $page == $i ? 'bg-blue-500 text-white' : 'bg-white text-blue-500'; ?> rounded">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Shopping Cart Implementation -->
    <script>
    $(document).ready(function() {
        $('.add-to-cart').click(function() {
            const courseId = $(this).data('course-id');
            
            $.ajax({
                url: 'api/add_to_cart.php',
                method: 'POST',
                data: { course_id: courseId },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        alert('Course added to your application cart!');
                        updateCartCount();
                    } else {
                        alert(data.message || 'You can only select up to 3 courses.');
                    }
                },
                error: function() {
                    alert('Error adding course to cart. Please try again.');
                }
            });
        });

        function updateCartCount() {
            $.ajax({
                url: 'api/get_cart_count.php',
                method: 'GET',
                success: function(response) {
                    const data = JSON.parse(response);
                    $('#cart-count').text(data.count);
                }
            });
        }
    });
    </script>

</body>
</html>

<?php mysqli_close($conn); ?>