<?php
session_start();

// Connect to PostgreSQL database
$conn = pg_connect("host=postgres port=5432 dbname=emmy user=postgres password=postgres");

// Check connection
if (!$conn) {
    echo "Error: Unable to connect to database";
    exit;
}

// Login function
function login($conn, $email, $password) {
    $query = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));
    $user = pg_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, create session
        $_SESSION['users_id'] = $user['id'];
        return true;
    }

    return false;
}

// Register function
function register($conn, $email, $password) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO users (email, password) VALUES ($1, $2)";
    pg_query_params($conn, $query, array($email, $hashed_password));
}

// Handle login form submission
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login($conn, $email, $password)) {
        // Login successful, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Login failed, display error message
        echo "Invalid email or password";
    }
}

// Display login form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>