<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Business Growth - Property Management System</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/img/logo/icon.png') }}">

    <!-- Flowbite, Tailwind CSS, and FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/build/assets/app-DspuE8pW.js') }}">
    <link rel="stylesheet" href="{{ asset('assets/build/assets/app-DudOFP__.css') }}">
</head>

<body class="flex items-center justify-center min-h-screen text-center text-white bg-gray-100">
    <div>
        <x-banner />

        <!-- Navbar -->
        <nav class="inline-block p-4 rounded-lg shadow-md dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <x-authentication-card-logo />
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="py-16 text-center text-gray-900">
            <h1 class="text-5xl font-extrabold">Simplify Your Property Management</h1>
            <p class="max-w-2xl mx-auto mt-4 text-lg">Track buildings, manage apartments, handle tenant information, and
                process rent payments - all in one system.</p>

            <!-- CTA Buttons -->
            <div class="mt-8 space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-6 py-3 text-lg font-semibold bg-green-600 rounded-lg shadow-md hover:bg-green-700">
                        <i class="mr-2 fas fa-tachometer-alt"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-6 py-3 text-lg font-semibold bg-yellow-500 rounded-lg shadow-md hover:bg-yellow-600">
                        <i class="mr-2 fas fa-play"></i> Get Started
                    </a>
                    <button data-modal-target="request-modal" data-modal-toggle="request-modal"
                        class="inline-flex items-center px-6 py-3 text-lg font-semibold text-white bg-gray-800 rounded-lg shadow-md hover:bg-gray-900">
                        <i class="mr-2 fas fa-info-circle"></i> Request to use system
                    </button>
                @endauth
            </div>

        </section>
    </div>

    @livewire('user.request.request-livewire')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>

</html>
