<?php
include '../includes/config.php';
include '../classes/Blog.php';

// Create a new instance of the Blog class
$blog = new Blog($pdo);

// Get the blog heading from the URL
$heading = isset($_GET['title']) ? $_GET['title'] : '';

if ($heading) {
    // Fetch blog details from the database
    $blogDetails = $blog->getBlogByHeading($heading);

    // If no blog found, redirect to a 404 page or show an error message
    if (!$blogDetails) {
        echo "Blog not found.";
        exit;
    }
} else {
    echo "Invalid blog heading.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blogDetails['heading']); ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-7">
                <div class="section-content-title">
                    <h2><?php echo htmlspecialchars($blogDetails['heading']); ?></h2>
                </div>
                <p><?php echo nl2br(htmlspecialchars($blogDetails['short_description'])); ?></p>
            </div>
            <div class="col-lg-5">
                <img src="../uploads/<?php echo htmlspecialchars($blogDetails['image']); ?>" class="img-fluid rounded card-img-top" alt="<?php echo htmlspecialchars($blogDetails['image']); ?>" style="max-width: 150px;">
            </div>
        </div>
        <div class="row mt-4">
            <?php echo $blogDetails['long_description']; ?>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
