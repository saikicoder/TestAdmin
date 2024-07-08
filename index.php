<?php
session_start();
require_once 'includes/config.php';
require_once 'classes/User.php';


$user = new User($pdo);

// If the user is logged in, redirect to the dashboard
if ($user->isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        header('Location: pages/dashboard.php');
        exit;
    } else {
        $login_error = "Invalid username or password";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">

	</head>
	<body class="img js-fullheight" style="background-image: url(images/bg.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Login</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	


              <form action="" class="signin-form" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input id="password-field" type="password" class="form-control" name="password" placeholder="Password" required>
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <?php if (isset($login_error)): ?>
                            <div class="alert alert-danger" id="loginError">
                                <?php echo htmlspecialchars($login_error); ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="form-control btn btn-primary submit px-3">Login</button>
                    </div>
            </form>
	          
		      </div>
				</div>
			</div>
		</div>
	</section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php if ($login_error): ?>
                $('#loginError').slideDown();
                setTimeout(function() {
                    $('#loginError').slideUp();
                }, 3000); // Hide after 3 seconds
            <?php endif; ?>
        });
    </script>
    <script>
        $(document).ready(function() {
            var id = "<?php echo $id; ?>";
            if (id) {
                $('#submitBtn').hide();
                $('#updateBtn').show();
            }
        });
    </script>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

	</body>
</html>

