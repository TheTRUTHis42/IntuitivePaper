<?php
//include_once "session.php";
session_start();

//database connection
$mysql = new mysqli(
    "webdev.iyaserver.com",
    "louisxie_user1",
    "sampleimport",
    "louisxie_IPImportTest"
);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// connection error test
if ($mysql->connect_errno) {
    echo "Database Connection Error:" . $mysql->connect_errno;
    exit();
}
echo "Debug: Script is executed.";

$pass = '';
$confirmPass = '';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $fName = trim($_POST['fname']);
    $lName = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $userName = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPassword']);



// Debug: Print values before the condition
    echo "Debug: Before condition - confirmPass = " . $confirmPass . ", pass = " . $pass;

    if ($sql = $mysql->prepare("SELECT * FROM users WHERE email = ?")) {
        $error = '';
        $sql->bind_param('s', $email);
        $sql->execute();
        // store results
        $sql->store_result();
        if ($sql->num_rows > 0) {
            $error .= "<p> The email is already registered</p>";
        }
        else {
            // validate password
            if (strlen($pass) < 8) {
                $error .= "<p> Password must be at least 8 chars long</p>";
                // Debug: Print values inside the condition
                echo "Debug: Inside condition - confirmPass = " . $confirmPass . ", pass = " . $pass;
            } elseif ($confirmPass !== $pass) {
                $error .= "<p> Passwords must match</p>";
// Debug: Print values inside the condition
                echo "Debug: Inside condition - confirmPass = " . $confirmPass . ", pass = " . $pass;
            }
        }
        if ($error == '') {
//            $userSQL = $mysql->prepare("INSERT INTO users (`username`, `fname`, `lname`, `email`) VALUES(?, ?, ?, ?)");
//            $userSQL->bind_param('ssss', $userName, $fName, $lName, $email);
//            $results = $userSQL->execute();

// Debug: Print values after the condition
            echo "Debug: After condition - confirmPass = " . $confirmPass . ", pass = " . $pass;

//            if (!$results) {
//                echo "Something went wrong: " . $userSQL->error;
//            } else {
                echo "Registration was successful";
//            }
        }


        $sql->close();
    }
}

?>

<html lang = "en">
<head>
    <title>Admin Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>

<div class="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
<div class="absolute top-0 w-full py-4 px-3" style="background: linear-gradient(to top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 100%);">
        <div class="max-w-6xl mx-auto flex flex-row items-center justify-between">
            <a href="../pages/search.php">
                <img src="assets/logo.svg" class="w-32" />
            </a>
            <div class="flex flex-row items-center space-x-2">
                <a href="../pages/login.php">
                    <button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Login</button>
                </a>
                <a href="pages/register.php">
                    <button style="border-color: #2D1F63; background-color: #2D1F63;" class="py-2 px-4 rounded-full border text-sm text-white">Register</button>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-32 py-8 bg-white">
        <div class="max-w-lg mx-auto">
            <form action="" method="POST" class="border border-gray-200 rounded-2xl shadow-sm p-8 space-y-4">
              <h1 class="text-2xl font-semibold"> Admin Registration</h1>
                <?php
                echo $error;

                ?>
              <div class="space-y-2">
                <label class="block">Email</label>
                <input type="email" name="email" placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
                <div class="space-y-2">
                <label class="block">First Name</label>
                <input type="text" name="fname" placeholder="John" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
                <div class="space-y-2">
                <label class="block">Last Name</label>
                <input type="text" name="lname" placeholder="Doe" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Username</label>
                <input type="text" name="username" placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Password</label>
                <input type="password" name="password" placeholder="**********" required class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Confirm Password</label>
                <input type="password" name="confirmPassword" placeholder="**********" required class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="">
                <button class="mt-4 text-white w-full py-2 px-3 rounded-full bg-blue-400 border-none hover:bg-blue-500 cursor-pointer">Register</button>
              </div>
              <p class="text-sm text-center text-gray-500">Already have an account? <a href="login.php" class="text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none font-medium">Login here</a></p>
            </form>
        </div>
    </div>
</div>
<script>
    // JavaScript to prevent form submission if there are errors
    document.querySelector('form').addEventListener('submit', function(event) {
        // Check if there are errors
        if ('<?php echo strlen($error); ?>' !== '0') {
            // Prevent the form from submitting
            event.preventDefault();
        }
    });
</script>
</body>
</html>

