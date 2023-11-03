<html lang = "en">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div className="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
    <div class="mt-32 py-8 bg-white">
        <div class="max-w-lg mx-auto">
            <form class="border border-gray-200 rounded-2xl shadow-sm p-8 space-y-4">
              <h1 class="text-2xl font-semibold">Register</h1>
              <div class="space-y-2">
                <label class="block">Email</label>
                <input type="email" placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Username</label>
                <input type="text" placeholder="Type here" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Password</label>
                <input type="password" placeholder="**********" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="space-y-2">
                <label class="block">Confirm Password</label>
                <input type="password" placeholder="**********" class="w-full py-2 px-4 rounded-lg border border-gray-200 shadow-sm" />
              </div>
              <div class="">
                <button class="mt-4 text-white w-full py-2 px-3 rounded-full bg-blue-400 border-none hover:bg-blue-500 cursor-pointer">Register</button>
              </div>
              <p class="text-sm text-center text-gray-500">Already have an account? <a href="" class="text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none font-medium">Login in here</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>

