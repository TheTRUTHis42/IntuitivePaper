<?php
//require_once "session.php";
session_start();
$mysql = new mysqli(
    "webdev.iyaserver.com",
    "louisxie_user1",
    "sampleimport",
    "louisxie_IPImportTest"
);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//connection error test
if($mysql->connect_errno){
    echo "Database Connection Error:". $mysql->connect_errno;
    exit();
}

$error = '';
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $username = trim($_POST['username']);
    $pass = trim($_POST['password']);

// validate username
    if(empty($username)){
        $error.= '<p>Please enter username.</p>';
    }
// validate email
    if(empty($pass)){
        $error.= '<p>Please enter password.</p>';
    }
    if(empty($error)) {
        if ($sql = $mysql->prepare("SELECT * FROM users WHERE username = ?")) {
            $sql->bind_param('s', $username);
            $results = $sql->execute();
            if (!$results) {
                echo "<hr>SQL Error: " . $mysql->error . "<br>";
                echo "Output SQL: " . $sql . "</hr>";
                exit();
            }
            $currentrow = $sql->fetch();
            if ($currentrow){
                if (password_verify($pass, $currentrow['password'])) {
//                $_SESSION["userid"] = $currentrow;
                    // logs the username
                    $_SESSION["username"] = $currentrow['username'];

                    // direct user to results page or whatever designated page we choose
                    header("location: ../pages/details.php");
                    exit();
                } else {
                    $error .= "<p>The password is not valid</p>";
                }
            } else {
                $error .= "<p>Your account does not exist </p>";
            }
            $sql->close();
        }
    }

}
?>
<html lang = "en">
<head>
    <title>Admin Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div className="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
  <div class="absolute top-0 w-full py-4 px-3" style="background: linear-gradient(to top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 100%);">
        <div class="max-w-6xl mx-auto flex flex-row items-center justify-between">
            <a href="../pages/search.php">
                <img src="../assets/logo.svg" class="w-32" />
            </a>
            <div class="flex flex-row items-center space-x-2">
                <a href="../pages/login.php">
                    <button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Login</button>
                </a>
                <a href="../pages/register.php">
                    <button style="border-color: #2D1F63; background-color: #2D1F63;" class="py-2 px-4 rounded-full border text-sm text-white">Register</button>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-32 py-8 bg-white">
        <div class="max-w-lg mx-auto">
            <form action="../pages/results.php" METHOD="post" class="border border-gray-200 rounded-2xl shadow-sm p-8 space-y-4">
              <h1 class="text-2xl font-semibold"> Admin Login</h1>
                <?php
                echo $error;
                ?>
              <div class="space-y-2">
                <label class="block">Username</label>
                <input type="text" placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Password</label>
                <input type="password" placeholder="**********" required = 1 class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="">
                <button class="mt-4 text-white w-full py-2 px-3 rounded-full bg-blue-400 border-none hover:bg-blue-500 cursor-pointer">Login</button>
              </div>
              <p class="text-sm text-center text-gray-500">New to Intuitive Paper? <a href="register.php" class="text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none font-medium">Register now</a></p>
            </form>
        </div>
        <?php

        ?>
    </div>
</div>
</body>
</html>

