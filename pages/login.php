<?php
session_start();

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $mysqli = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute the query
    // Include is_email_verified in your SELECT statement
    $stmt = $mysqli->prepare("SELECT password, is_email_verified FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Check if the email is verified
            if ($row['is_email_verified'] == 0) {
                // User's email is not verified
                echo "Your email is not verified. Please check your email.";
                exit();
            }
            // Email is verified, proceed with login
            // Redirect to the main page
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['username'] = $username; // Optional: to display or use the username
            header("Location: https://louisxie.webdev.iyaserver.com/acad276/Intuitive%20Paper/search.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password.";
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
    <div class="absolute top-0 w-full py-4 px-3" style="background: linear-gradient(to top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 100%);">
        <div class="max-w-6xl mx-auto flex flex-row items-center justify-between">
            <a href="search.php">
                <img src="assets/logo.svg" class="w-32" />
            </a>
            <div class="flex flex-row items-center space-x-2">
                <a href="login.php">
                    <button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Login</button>
                </a>
                <a href="register.php">
                    <button style="border-color: #2D1F63; background-color: #2D1F63;" class="py-2 px-4 rounded-full border text-sm text-white">Register</button>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-32 py-8 bg-white">
        <div class="max-w-lg mx-auto">
            <form action="login.php" method="post" class="border border-gray-200 rounded-2xl shadow-sm p-8 space-y-4">
                <h1 class="text-2xl font-semibold">Login</h1>
                <?php if (!empty($login_error)): ?>
                    <p style='color: red;'><?php echo $login_error; ?></p>
                <?php endif; ?>
                <div class="space-y-2">
                    <label class="block">Username</label>
                    <input type="text" name="username" required placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="block">Password</label>
                    <input type="password" name="password" required placeholder="**********" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
                </div>
                <div class="">
                    <button type="submit" class="mt-4 text-white w-full py-2 px-3 rounded-full bg-blue-400 border-none hover:bg-blue-500 cursor-pointer">Login</button>
                </div>
                <p class="text-sm text-center text-gray-500">New to Intuitive Paper? <a href="register.php" class="text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none font-medium">Register now</a></p>
            </form>
        </div>
    </div>
</div>

</body>
</html>




<?php
session_start();

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $mysqli = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute the query
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // After checking the username and password
            if ($row['is_email_verified'] == 0) {
                // User's email is not verified
                echo "Your email is not verified. Please check your email.";
                exit();
            }
            // Proceed with login
            // Redirect to the main page
            header("Location: https://louisxie.webdev.iyaserver.com/acad276/Intuitive%20Paper/search.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password.";
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
}
?>
