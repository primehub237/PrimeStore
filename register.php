<?php
include 'includes/connect.php';
include 'includes/functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = sanitize($_POST['fname']);
    $lname = sanitize($_POST['lname']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer';

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (first_name, last_name, email, password, role)
                VALUES ('$fname', '$lname', '$email', '$password', '$role')";
        if ($conn->query($sql)) {
            $msg = "Registration successful! Please log in.";
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <h3 class="text-center mb-4">Create an Account</h3>
    <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="fname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
            </div>
        </div>
        <input type="email" name="email" class="form-control mb-3" placeholder="Email Address" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
