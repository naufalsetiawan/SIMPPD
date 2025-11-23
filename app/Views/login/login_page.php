<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMP | Log In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body class="font-[Montserrat]">
    <div class="flex justify-center items-center relative w-screen h-screen bg-cover bg-center" style="background-image: url('/img/DSC00870.jpg');">
        <!-- Semi-transparent overlay -->
        <div class="absolute inset-0 bg-black opacity-60"></div>

        <div class="flex justify-center items-center w-5/6 h-5/6 bg-[rgba(255,255,255,0.4)] rounded-md z-30 backdrop-blur-md shadow-md">
            <!-- Logo -->
            <div class="h-full w-1/2 px-16 py-8 justify-center hidden lg:flex lg:flex-col">
                <div class="flex justify-start">
                    <img src="/img/LOGO KOMDIGI-HORIZONTAL 2-WHITE.png" class="w-52 mb-4" alt="Logo Komdigi">
                </div>

                <!-- Judul Aplikasi -->
                <h1 class="text-3xl text-left font-bold text-white mb-2">
                    Sistem Informasi Manajemen Pengawasan Perjalanan Dinas
                </h1>
                <h1 class="text-2xl text-left font-semibold text-white">
                    Balai Monitor SFR Kelas I Semarang
                </h1>
            </div>

            <div class="w-full lg:w-1/2 h-full flex flex-col justify-between rounded-r-md bg-white">
                <div class="px-16 pt-16">
                    <div class="flex items-start">
                        <i class="fa-solid fa-arrow-right-to-bracket text-blue-500 mr-4 text-3xl font-bold"></i>

                        <h1 class="text-3xl font-bold text-blue-500">
                            Sign In
                        </h1>
                    </div>
                </div>

                <div class="flex items-center px-16">
                    <h1 class="text-xl font-semibold text-gray-600">
                        Masuk ke Akun Anda
                    </h1>
                </div>
                <!-- Login Card -->
                <div class="w-full px-16 mx-auto rounded-md">

                    <form method="POST" action="/login" class="px-0">
                        <!-- Input Email -->
                        <div class="flex flex-col gap-x-2 mb-2">
                            <label for="email" class="text-gray-600 font-semibold mb-2"> Username </label>
                            <div class="w-full text-sm flex items-center p-2 border border-gray-300 rounded-lg">
                                <i class="fa-solid fa-user text-gray-500 mr-2"></i>
                                <input type="username" name="username" class="text-gray-500 w-full border-0 focus:ring-0 focus:outline-none" placeholder="Masukkan Username">
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div class="flex flex-col gap-x-2 mb-2">
                            <label for="password" class="text-gray-600 font-semibold mb-2"> Password </label>
                            <div class="w-full text-sm flex items-center p-2 border border-gray-300 rounded-lg">
                                <i class="fa-solid fa-lock text-gray-500 mr-2"></i>
                                <input type="password" name="password" class="text-gray-500 w-full border-0 focus:ring-0 focus:outline-none" placeholder="Masukkan Password">
                            </div>
                        </div>

                        <!-- Validation -->
                        <div class="bg-red-200 h-10 flex items-center p-2 my-4 rounded-sm <?php if ($error = session()->getFlashdata('error')): ?>block<?php else: ?>invisible<?php endif; ?>">
                            <span class="text-red-600 text-sm">
                                <?= esc($error ?? '') ?>
                            </span>
                        </div>

                        <!-- Tombol Log In -->
                        <button type="submit" class="bg-blue-500 px-8 py-2 rounded-sm w-full hover:bg-blue-700 focus:bg-blue-800">
                            <span class="font-bold text-white text-sm">
                                Sign In
                            </span>
                        </button>
                    </form>
                </div>
                <span class="px-8 py-4 text-center text-base text-gray-600">
                    &copy;<span id="year"></span> Balai Monitor SFR Kelas I Semarang. All rights reserved.
                </span>
            </div>
        </div>
    </div>

    <script>
        const d = new Date();
        let year = d.getFullYear();
        document.getElementById("year").innerHTML = year;
    </script>
</body>

</html>