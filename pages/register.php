<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div className="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
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
            <form action="register.php" method="post" class="border border-gray-200 rounded-2xl shadow-sm p-8 space-y-4">
                <h1 class="text-2xl font-semibold">Register</h1>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $email = $_POST['email'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $confirmPassword = $_POST['confirmPassword'];

                    if ($password === $confirmPassword) {
                        $mysqli = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
                        if ($mysqli->connect_error) {
                            die("Connection failed: " . $mysqli->connect_error);
                        }

                        // Generate a unique token and hash the password
                        $email_verification_token = bin2hex(random_bytes(16));
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                        // Insert the user along with the token
                        $stmt = $mysqli->prepare("INSERT INTO users (email, username, password, email_verification_token) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $email, $username, $passwordHash, $email_verification_token);
                        $stmt->execute();

                        // Send an email with the verification link
                        $verification_link = "https://louisxie.webdev.iyaserver.com/acad276/Intuitive%20Paper/verify_email.php?token=" . $email_verification_token;
                        mail($email, "Verify your email", "Please click on this link to verify your email: $verification_link");

                        echo "Registration successful. Please check your email for confirmation.";

                        $stmt->close();
                        $mysqli->close();
                    } else {
                        echo "<p style='color: red;'>Please check and re-enter your password.</p>";
                    }
                }
                ?>
                <div class="space-y-2">
                    <label class="block">Email</label>
                    <input type="email" name="email" required placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="block">Username</label>
                    <input type="text" name="username" required placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="block">Password</label>
                    <input type="password" name="password" required placeholder="**********" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="block">Confirm Password</label>
                    <input type="password" name="confirmPassword" required placeholder="**********" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
                </div>
                <div class="">
                    <button type="submit" class="mt-4 text-white w-full py-2 px-3 rounded-full bg-blue-400 border-none hover:bg-blue-500 cursor-pointer">Register</button>
                </div>
                <p class="text-sm text-center text-gray-500">Already have an account? <a href="login.php" class="text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none font-medium">Login here</a></p>
            </form>
        </div>
    </div>
</div>

</body>
</html>




