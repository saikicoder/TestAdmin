<?php
session_start();

$message = $_SESSION['message'] ?? '';
$message_type = $_SESSION['message_type'] ?? '';

// Clear session variables to prevent the message from displaying again on refresh
unset($_SESSION['message']);
unset($_SESSION['message_type']);

require_once '../includes/config.php';
require_once '../classes/User.php';
require_once '../classes/Blog.php';

$user = new User($pdo);

if (!$user->isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

$blog = new Blog($pdo);
$totalBlogs = $blog->countBlogs();

$title = "dashboard";
include_once("head_nav.php");
?>
        <div id="layoutSidenav_content">
                <main>
                    
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="dashboard-card">
                                    <h4>Total Blogs</h4>
                                    <p><?php echo $totalBlogs; ?></p>
                                </div>
                            </div>
                            <!-- Add more dashboard cards here later -->
                        </div>
                    </div>
    
    
                </main>
    
    
    <?php
include_once("footer.php");
        ?>