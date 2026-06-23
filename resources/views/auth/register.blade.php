<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Business Growth</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/img/logo/logo_icon.png') }}">

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS, FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        uds: {
                            blue: '#003b70',
                            orange: '#f39200',
                            green: '#2d9d3f',
                            navy: '#0b2545',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="relative min-h-screen font-sans bg-slate-50 overflow-x-hidden flex items-center justify-center p-4 lg:p-8">
    <!-- Background Glows -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] rounded-full bg-blue-100/30 blur-[120px]"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[60%] rounded-full bg-green-100/20 blur-[120px]"></div>
        <div class="absolute top-[30%] left-[40%] w-[40%] h-[40%] rounded-full bg-orange-50/30 blur-[120px]"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-5xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-16 py-12 px-4 lg:px-8 relative">
        
        <!-- Left Column: Welcome & Info -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center relative z-10">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 mb-8 lg:mb-10">
                <img src="{{ asset('assets/img/logo/white-logo.png') }}" alt="My Business Growth Logo" class="h-11 w-auto object-contain">
            </div>

            <!-- Welcome Title -->
            <h1 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold text-uds-navy leading-tight tracking-tight mb-5">
                Join as Landlord
            </h1>

            <!-- Description -->
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-md mb-8">
                Create a landlord workspace to manage properties, generate invoices, track rental incomes, and get complete tax reports.
            </p>

            <!-- Back Link -->
            <a href="/" class="inline-flex items-center gap-2 text-uds-orange hover:text-uds-orange/80 text-sm sm:text-base font-semibold hover:underline">
                &larr; Back to home
            </a>

            <!-- Dot Grid Accent (Bottom Left) -->
            <div class="absolute -bottom-24 -left-12 opacity-30 hidden lg:block select-none pointer-events-none">
                <svg width="100" height="120" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="15" cy="15" r="3" fill="#cbd5e1" />
                    <circle cx="35" cy="15" r="3" fill="#cbd5e1" />
                    <circle cx="55" cy="15" r="3" fill="#cbd5e1" />
                    <circle cx="75" cy="15" r="3" fill="#cbd5e1" />
                    <circle cx="15" cy="35" r="3" fill="#cbd5e1" />
                    <circle cx="35" cy="35" r="3" fill="#cbd5e1" />
                    <circle cx="55" cy="35" r="3" fill="#cbd5e1" />
                    <circle cx="75" cy="35" r="3" fill="#cbd5e1" />
                    <circle cx="15" cy="55" r="3" fill="#cbd5e1" />
                    <circle cx="35" cy="55" r="3" fill="#cbd5e1" />
                    <circle cx="55" cy="55" r="3" fill="#cbd5e1" />
                    <circle cx="75" cy="55" r="3" fill="#cbd5e1" />
                    <circle cx="15" cy="75" r="3" fill="#cbd5e1" />
                    <circle cx="35" cy="75" r="3" fill="#cbd5e1" />
                    <circle cx="55" cy="75" r="3" fill="#cbd5e1" />
                    <circle cx="75" cy="75" r="3" fill="#cbd5e1" />
                </svg>
            </div>
        </div>

        <!-- Right Column: Register Card -->
        <div class="w-full lg:w-[450px]">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-[0_20px_50px_rgba(240,244,248,0.7)] border border-slate-100">
                <div class="mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-uds-navy">Register Account</h2>
                    <p class="text-slate-400 text-xs sm:text-sm mt-1">Get started with a landlord account</p>
                </div>

                <!-- Validation Errors & Session Status -->
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-uds-orange/20 focus:border-uds-orange transition-all duration-200" placeholder="e.g. John Doe" />
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            class="block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-uds-orange/20 focus:border-uds-orange transition-all duration-200" placeholder="name@example.com" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl pl-4 pr-10 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-uds-orange/20 focus:border-uds-orange transition-all duration-200" placeholder="Min. 8 characters" />
                            <button type="button" onclick="togglePasswordVisibility('password', 'password-toggle-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i id="password-toggle-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                class="block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl pl-4 pr-10 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-uds-orange/20 focus:border-uds-orange transition-all duration-200" placeholder="Confirm your password" />
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-password-toggle-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i id="confirm-password-toggle-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full inline-flex items-center justify-center bg-uds-blue hover:bg-[#002f5a] text-white text-sm sm:text-base font-semibold px-6 py-3 rounded-xl shadow-lg shadow-uds-blue/20 hover:shadow-uds-blue/30 hover:-translate-y-0.5 transition-all duration-200">
                            Register
                        </button>
                    </div>

                    <!-- Already Registered Link -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm text-slate-400 hover:text-slate-600 font-medium hover:underline">
                            Already registered? Sign in
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="absolute bottom-4 left-0 right-0 text-center py-2 px-4">
        <p class="text-[10px] sm:text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} My Business Growth. All rights reserved. | Powered by <span class="text-uds-blue font-semibold">Ubwenge Digital Solutions Ltd</span>
        </p>
    </footer>

    <!-- Password visibility toggle script -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
