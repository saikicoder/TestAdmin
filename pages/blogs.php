<?php
session_start();

$message = $_SESSION['message'] ?? '';
$message_type = $_SESSION['message_type'] ?? '';

// Clear session variables to prevent the message from displaying again on refresh
unset($_SESSION['message']);
unset($_SESSION['message_type']);
print_r(1);exit;
require_once '../includes/config.php';
require_once '../classes/User.php';
require_once '../classes/blog.php';

$user = new User($pdo);

if (!$user->isLoggedIn()) {
    
    header('Location: ../index.php');
    exit;
}


// Initialize Blog object
$blog = new Blog($pdo);
// Fetch all blog posts
$posts = $blog->readAll();
$totalBlogs = $blog->countBlogs();

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    
    // Process delete request
    $delete_id = $_GET['delete_id'];

    if($blog->delete($delete_id))
    {
        // Simulate a successful operation (e.g., database)
        $_SESSION['message'] = 'Blog post deleted successfully!';
        $_SESSION['message_type'] = 'success';
        header('Location: blogs.php');
    } else {
        // Simulate a successful operation (e.g., database)
        $_SESSION['message'] = 'Invalid data!';
        $_SESSION['message_type'] = 'danger';
        header('Location: blogs.php');
    }

    
}



$title = "Blog";
include_once("head_nav.php");
?>



<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <!-- Bootstrap JS Bundle with Popper -->
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Modal- Long Description -->
<div class="modal fade" id="longDescriptionModal" tabindex="-1" aria-labelledby="longDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="longDescriptionModalLabel">Long Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="longDescriptionContent"></div>
                </div>
            </div>
        </div>
    </div>

   
    

    <script>
        $(document).ready(function() {
            $('.viewBtn').click(function() {
                var longDescription = $(this).data('longdesc');
                $('#longDescriptionContent').html(longDescription);
                $('#longDescriptionModal').modal('show');
            });
        });
    </script>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Blogs</h1>
                        <!--<ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Blogs</li>
                        </ol>-->
                        <div class="card mb-4">
                            
                        </div>
                        <div class="card mb-4">
                            <div class="d-flex align-items-center justify-content-between border rounded p-3">
                                <div></div>
                                <div class="text-end">
                                    
                                    <a type="button" href="Blog.php" class="btn btn-info"><i class="fa fa-plus me-2"></i>Add Blog</a>
                                </div>
                            </div>
                            <div class="card-body">
                            <div id="statusAlert" class="alert" style="display: none;"></div>
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Heading</th>
                                            <th>Short Description</th>
                                            <th>Long description</th>
                                            <th>Update Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Heading</th>
                                            <th>Short Description</th>
                                            <th>Long description</th>
                                            <th>Update Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>

                                            <?php $i=1;if (!empty($posts)) : ?>
                                                <?php foreach ($posts as $post) : ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo htmlspecialchars($post['heading']); ?></td>
                                                        <td><?php echo htmlspecialchars($post['short_description']); ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-sm viewBtn" data-longdesc="<?php echo htmlspecialchars($post['long_description']); ?>">View</button>
                            
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary <?php echo $post['status'] == 1 ? 'btn-inactive' : 'btn-active'; ?> toggle-status" data-id="<?php echo $post['id']; ?>" data-status="<?php echo $post['status'] == 1 ? 0 : 1; ?>">
                                                                <?php echo $post['status'] == 1 ? 'Deactivate' : 'Activate'; ?>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <a href="blog.php?id=<?php echo html_entity_decode($post['id']); ?>" ><i class="fas fa-edit" style="color: green;font-size: large;margin-right: 4px;"></i> </a>
                                                            <a href="blogs.php?delete_id=<?php echo html_entity_decode($post['id']); ?>" ><i class="fas fa-trash-alt" style="color: red;font-size: large"></i> </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No blog posts found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

         
                <script>
                    $(document).ready(function() {
                        $('.toggle-status').click(function() {
                            
                            var button = $(this);
                            var id = button.data('id');
                            var status = button.data('status');
                               
                            $.ajax({
                                
                                url: '../includes/ajaxUpdates.php',
                                type: 'POST',
                                data: { id: id, status: status },
                                success: function(response) {
                                    
                                    var result = JSON.parse(response);
                                    
                                    if (result.success) {
                                        var newStatus = status == 1 ? 'Active' : 'Inactive';
                                        var newButtonText = status == 1 ? 'Deactivate' : 'Activate';
                                        button.data('status', status == 1 ? 0 : 1);
                                        $('#status-' + id).text(newStatus);
                                        button.text(newButtonText);
                                        button.toggleClass('btn-active btn-inactive');

                                        var alertClass = status == 1 ? 'alert-success' : 'alert-warning';
                                        $('#statusAlert').removeClass('alert-success alert-warning').addClass(alertClass).text('Status updated successfully!').show();
                                        setTimeout(function() {
                                        $('#statusAlert').hide();
                                        }, 3000);
                                    } else {
                                        alert('Failed to update status: ' + result.message);
                                    }
                                }
                            });
                        });
                    });
                </script>
                
        <?php
include_once("footer.php");
        ?>
