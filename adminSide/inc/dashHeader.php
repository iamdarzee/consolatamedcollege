<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sister Leonella Consolata Medical College Admin Dashboard">
    <meta name="author" content="DARZEE">
    <title>Admin Dashboard</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../media/lock.jpg" type="image/svg+xml">

    <!-- External CSS and Font Awesome -->
    <script src="https://kit.fontawesome.com/241ef04b1d.js" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: black; /* Light gray background for contrast */
        }
        .navbar {
            background-color: #343a40; /* Dark background for navbar */
        }
        .navbar-brand {
            font-size: 1.5rem; /* Larger font size for brand */
        }
        .sb-sidenav-menu {
            padding-top: 20px; /* Padding for sidenav */
        }
        .sb-sidenav-menu .nav-link {
            padding: 10px 20px; /* Spacing for links */
            color: #adb5bd !important; /* Light gray text color */
            transition: background-color 0.3s ease; /* Smooth hover effect */
        }
        .sb-sidenav-menu .nav-link:hover {
            background-color: #495057; /* Dark background on hover */
        }
        .sb-sidenav-footer {
            padding: 20px; /* Padding for footer */
            background-color: #212529; /* Dark footer background */
            color: #adb5bd; /* Light text color */
        }
        .sb-sidenav-footer .small {
            color: #6c757d; /* Lighter gray for "Logged in as" text */
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand" href="../panel/<?php echo basename($_SERVER['PHP_SELF']); ?>">
            <?php echo ucwords(str_replace("-", " ", basename($_SERVER['PHP_SELF'], ".php"))); ?>
        </a>
    </nav>

    <!-- Sidebar Navigation -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <h2 class="text-gray-400 uppercase tracking-wider text-xs">Main</h2>
                        <a class="block px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 rounded" href="../panel/courses-panel.php">
                            <i class="fas fa-utensils mr-2"></i> Courses
                        </a>
                        <a class="block px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 rounded" href="../panel/staff-panel.php">
                            <i class="fas fa-people-group mr-2"></i> Staff
                        </a>
                        <a class="block px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 rounded" href="../panel/students-account-panel.php">
                            <i class="fas fa-eye mr-2"></i> Student Accounts
                        </a>
                        <a class="block px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 rounded" href="../StaffLogin/login.php">
                            <i class="fas fa-sign-in-alt mr-2"></i> Staff Login
                        </a>
                        <a class="block px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 rounded" href="../StaffLogin/logout.php">
                            <i class="fas fa-lock-open mr-2"></i> Log out
                        </a>
                    </div>
                </div>

                <!-- Footer Section for Logged-in Information -->
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php if (isset($_SESSION['logged_staff_id']) && isset($_SESSION['logged_staff_name'])): ?>
                        <div>Staff ID: <?php echo $_SESSION['logged_staff_id']; ?></div>
                        <div>Staff Name: <?php echo $_SESSION['logged_staff_name']; ?></div>
                    <?php else: ?>
                        <div>Not logged in</div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
