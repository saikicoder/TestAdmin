<?php
include '../includes/config.php';
include '../classes/Blog.php';

$blog = new Blog($pdo);

$limit = 12; // Number of blogs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalBlogs = $blog->countBlogs();
$totalPages = ceil($totalBlogs / $limit);

$blogs = $blog->getAllActiveBlogs($offset, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            cursor: pointer;
        }
        .card:hover {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <?php foreach ($blogs as $blog) : ?>
                <div class="col-lg-4 col-md-6 my-3">
                    <div class="card h-100" onclick="window.location.href='blog-detail.php?title=<?php echo urlencode($blog['heading']); ?>'">
                        <img src="../uploads/<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($blog['heading']); ?>">
                        <div class="card-body">
                            <a href="blog-detail.php?title=<?php echo urlencode($blog['heading']); ?>"><h5 class="card-title"><?php echo htmlspecialchars($blog['heading']); ?></h5></a>
                            <p class="card-text"><?php echo htmlspecialchars($blog['short_description']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1) { echo 'disabled'; } ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php if($page == $i) { echo 'active'; } ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if($page >= $totalPages) { echo 'disabled'; } ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
