<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan data user
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password, $role);
        $stmt->fetch();

        // Verifikasi password langsung
        if ($password === $db_password) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role; // Simpan role dalam sesi

            if ($role === 'admin') {
                header('Location: admin.php'); // Redirect ke halaman admin
            } else {
                header('Location: index.php'); // Redirect ke halaman user
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>

<body class="flex items-center justify-center h-screen bg-body">

    <div class="flex flex-col justify-center items-center">
        <h1 class="color text-8xl m-2 mulish">LISA</h1>
        <h3 class="text-white m-4 text-3xl font-mulish tracking-wider">List Inventaris Stok Aset</h3>
        <button id="loginButton" class="px-8 py-2.5 text-black bg-white rounded-full mt-2 mulish">Login</button>
    </div>

    <!-- Popup Background -->
    <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <!-- Popup Form -->
        <div class="glass rounded-lg shadow-lg px-12 py-8 w-full max-w-md transition-all duration-300 transform scale-50 opacity-0 popup-content">
            <h2 class="text-2xl mulish mb-4 text-center login">Login</h2>

            <form action="" method="POST" class="mulish">
                <label class="block mb-2 text-white">Username:</label>
                <div class="relative flex items-center">
                    <img src="Person.svg" alt="" class="absolute left-3 w-5 h-5 top-3">
                    <input type="text" class="w-full pl-10 p-2 mb-4 border border-gray-300 rounded" placeholder="username" name="username" required>
                </div>

                <label class="block mb-2 text-white">Password:</label>
                <div class="relative flex items-center">
                    <img src="Lock.svg" alt="" class="absolute left-3 w-4 h-4 top-3">
                    <input name="password" type="password" class="w-full pl-10 p-2 mb-4 border border-gray-300 rounded pr-10" placeholder="password" id="password" required>
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-gray-500">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <button type="submit" class="w-full py-2 mt-4 text-white bg-body rounded outline">Login</button>
            </form>
            <?php if (isset($error)) echo "<p class='text-red-500 text-center'>$error</p>"; ?>
            <button id="closeButton" class="w-full py-2 mt-4 text-white mulish">Close</button>
        </div>
    </div>

    <script>
        const loginButton = document.getElementById('loginButton');
        const popup = document.getElementById('popup');
        const closeButton = document.getElementById('closeButton');
        const popupContent = document.querySelector('.popup-content');

        loginButton.addEventListener('click', () => {
            popup.classList.remove('hidden');
            setTimeout(() => {
                popupContent.classList.remove('scale-50', 'opacity-0');
            }, 10);
        });

        closeButton.addEventListener('click', () => {
            popupContent.classList.add('scale-50', 'opacity-0');
            setTimeout(() => {
                popup.classList.add('hidden');
            }, 300);
        });

        window.addEventListener('click', (event) => {
            if (event.target === popup) {
                popupContent.classList.add('scale-50', 'opacity-0');
                setTimeout(() => {
                    popup.classList.add('hidden');
                }, 300);
            }
        });

        function togglePassword() {
            const passwordField = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        }
    </script>

    <style>
        .color {
            background: linear-gradient(90deg, rgba(255, 194, 58, 1) 0%, rgba(81, 210, 255, 1) 33%, rgba(103, 67, 255, 1) 66%, rgba(255, 96, 181, 1) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .mulish {
            font-family: "Mulish", sans-serif;
            font-weight: 700;
        }

        .glass {
            background: rgba(255, 255, 255, 0.24);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .font-mulish {
            font-family: "Mulish", sans-serif;
            font-weight: 400;
        }

        .bg-body {
            background-color: rgba(13, 13, 13, 1);
        }

        .login {
            background: linear-gradient(90deg, rgba(255, 194, 58, 1) 0%, rgba(81, 210, 255, 1) 33%, rgba(103, 67, 255, 1) 66%, rgba(255, 96, 181, 1) 100%);
            background-size: 18%;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }
    </style>
</body>
</html>
