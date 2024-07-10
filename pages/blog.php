<?php
session_start();
require_once '../includes/config.php';
require_once '../classes/User.php';
require_once '../classes/Blog.php';

$user = new User($pdo);
// Initialize Blog object
$blogs = new Blog($pdo);

if (!$user->isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    //print_r($_POST['id']);exit;
    // Get form data
    $heading = $_POST['heading'];
    $short_description = $_POST['short_description'];
    $long_description = $_POST['long_description'];
    $user_id = $_SESSION['user_id'];
    $image = $_FILES['image'];

    if(isset($_POST['id'])){
            // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
                            
            // Retrieve blog data based on ID
            $blog = $blogs->read($id); 

            // Check if blog data was found
            if (!$blog) {
                 // Simulate an error
                 $_SESSION['message'] = 'Failed to update blog post.';
                 $_SESSION['message_type'] = 'danger';
                header('Location: dashboard.php');
            }else{
                // Update blog data
                if ($blogs->update($id, $user_id, $heading, $short_description, $long_description, $image)) {
                        // Simulate a successful operation (e.g., database insertion)
                        $_SESSION['message'] = 'Blog post updated successfully!';
                        $_SESSION['message_type'] = 'success';
                        header('Location: blogs.php');
                } else {
                        // Simulate an error
                        $_SESSION['message'] = 'Failed to update blog post.';
                        $_SESSION['message_type'] = 'danger';
                        header('Location: blogs.php');
                }
            }
        }
    }else{

        // Create a new blog post
        if($blogs->create($user_id, $heading, $short_description, $long_description, $image))
        {
            // Simulate a successful operation (e.g., database insertion)
            $_SESSION['message'] = 'Blog post created successfully!';
            $_SESSION['message_type'] = 'success';
            header('Location: blogs.php');
        } else {
            $error = "Invalid data";
        }

    }

}

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    
    // Initialize variables
    $id = $_GET['id'] ?? null;
    $message = '';
    
    // Initialize Blog object
    $blogs = new Blog($pdo);

    // Check if ID parameter is provided in the URL
    if ($id) {
    // Retrieve blog data based on ID
    $blog = $blogs->read($id); 
    

    // Check if blog data was found
    if (!$blog) {
        header('Location: dashboard.php');
    }
    }

    // Function to escape HTML special characters to prevent XSS attacks
    function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

}

$title = "Blog";
include_once("head_nav.php");
?>
    <script>
        $(document).ready(function() {
            var id = "<?php echo $id; ?>";
            if (id) {
                $('#submitBtn').hide();
                $('#updateBtn').show();
            }
        });
    </script>
    
    <!-- Include CKEditor script -->
    <script src="../ckeditor/ckeditor.js"></script>
    
    
    <script>
        $(document).ready(function() {
            var id = "<?php echo $id; ?>";
            if (id) {
                $('#submitBtn').hide();
                $('#updateBtn').show();
            }
        });
    </script>
 
        <div id="layoutSidenav_content">
                <main>
                    

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><?php echo !isset($blogs) ? 'Create Blog' : 'Update Blog'; ?></h4>
                    </div>
                    <div class="card-body">
                        <form id="myForm" action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="heading">Heading</label>
                                <input type="text" class="form-control" name="heading" maxlength="45" value="<?php echo escape($blog['heading'] ?? ''); ?>" required><br><br>
                                
                            </div>
                            <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea id="short_description" class="form-control" name="short_description" rows="4" cols="50" required><?php echo escape($blog['short_description'] ?? ''); ?></textarea><br><br>
                        
                            </div>
                            <div class="form-group">
                                <label>Long Description</label>
                                <textarea id="editor1" class="form-control" name="long_description" rows="3"><?php echo escape($blog['long_description'] ?? ''); ?></textarea>  <br><br>                              
    <script>
            CKEDITOR.replace('editor1');
    </script>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control-file" id="image" name="image" <?php echo isset($blog) ? '' : 'required'; ?> onchange="previewImage(event)">

                                <?php if (isset($blog) && $blog['image']): ?>
                                    <div class="mt-3">
                                        <img id="image-preview" src="../uploads/<?php echo $blog['image']; ?>" alt="Blog Image" style="max-width: 150px;">
                                    </div>
                                <?php else: ?>
                                    <div class="mt-3">
                                        <img id="image-preview" alt="Image Preview" style="max-width: 150px; display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if(isset($_GET['id'])){?><input type="hidden" class="form-control" name="id" maxlength="6" value="<?php echo escape($blog['id'] ?? ''); ?>" required> <?php } ?>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                            <button type="submit" class="btn btn-warning" id="updateBtn" style="display: none;">Update</button>

                             
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
                </main>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                
                var output = document.getElementById('image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    
    <?php
include_once("footer.php");
        ?>