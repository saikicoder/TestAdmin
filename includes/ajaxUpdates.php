<?php
include 'config.php';
include '../classes/Blog.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $blog = new Blog($pdo);
    $id = $_POST['id'];
    $status = $_POST['status'];
   
    
    if ($blog->updateStatus($id, $status)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

?>


