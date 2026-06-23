<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Business Growth - Property Management System</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/img/logo/logo_icon.png') }}">

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Flowbite, Tailwind CSS, and FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
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

    <style>
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes float-medium {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-16px); }
        }
        @keyframes float-fast {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float-1 { animation: float-slow 6s ease-in-out infinite; }
        .animate-float-2 { animation: float-medium 7s ease-in-out infinite 0.5s; }
        .animate-float-3 { animation: float-fast 5s ease-in-out infinite 1s; }
        .animate-float-4 { animation: float-slow 8s ease-in-out infinite 1.5s; }
    </style>
</head>

<body class="relative min-h-screen font-sans bg-slate-50 overflow-x-hidden flex flex-col items-center justify-between p-4 lg:p-8">
    <!-- Background Glows -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] rounded-full bg-blue-100/30 blur-[120px]"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[60%] rounded-full bg-green-100/20 blur-[120px]"></div>
        <div class="absolute top-[30%] left-[40%] w-[40%] h-[40%] rounded-full bg-orange-50/30 blur-[120px]"></div>
    </div>

    <!-- Banner notifications -->
    <x-banner />

    <!-- Main Container -->
    <div class="w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-16 py-12 px-4 lg:px-8 relative flex-grow">
        
        <!-- Left Column: Branding and CTA -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center relative z-10">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 mb-8 lg:mb-10">
                <img src="{{ asset('assets/img/logo/white-logo.png') }}" alt="My Business Growth Logo" class="h-11 w-auto object-contain">
            </div>

            <!-- Headings -->
            <h1 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold text-uds-navy leading-[1.15] tracking-tight mb-5">
                Simplify Your <br>
                Property <span class="text-uds-green">Management</span>
            </h1>

            <!-- Description -->
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-lg mb-6 lg:mb-8">
                Manage buildings, coordinate apartments, track tenant information, and process rent payments. Our system delivers automated invoicing, real-time occupancy insights, and streamlined maintenance resolution all in one platform.
            </p>

            <!-- Actions -->
            <div class="flex flex-col items-start gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center bg-uds-blue hover:bg-[#002f5a] text-white text-sm sm:text-base font-semibold px-6 py-3.5 rounded-xl shadow-lg shadow-uds-blue/20 hover:shadow-uds-blue/30 hover:-translate-y-0.5 transition-all duration-200">
                        Go to Dashboard
                    </a>
                @else
                    <button data-modal-target="request-modal" data-modal-toggle="request-modal" class="inline-flex items-center justify-center bg-uds-blue hover:bg-[#002f5a] text-white text-sm sm:text-base font-semibold px-6 py-3.5 rounded-xl shadow-lg shadow-uds-blue/20 hover:shadow-uds-blue/30 hover:-translate-y-0.5 transition-all duration-200">
                        Request account
                    </button>
                    
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                        You have account? <a href="{{ route('login') }}" class="text-uds-orange hover:text-uds-orange/80 font-semibold hover:underline">login</a>
                    </p>
                @endauth
            </div>

            <!-- Contact Information -->
            <div class="mt-10 space-y-1.5 text-xs sm:text-sm text-slate-500 font-medium">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Call</span>
                    <a href="tel:+250788515572" class="text-uds-orange hover:underline">+250 788 515 572</a>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Email</span>
                    <a href="mailto:inf@ubwengedigitalsolutions.com" class="text-uds-orange hover:underline">inf@ubwengedigitalsolutions.com</a>
                </div>
            </div>

            <!-- Dot Grid Accent (Bottom Left) -->
            <div class="absolute -bottom-16 -left-12 opacity-40 hidden lg:block select-none pointer-events-none">
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
                    <circle cx="15" cy="95" r="3" fill="#cbd5e1" />
                    <circle cx="35" cy="95" r="3" fill="#cbd5e1" />
                    <circle cx="55" cy="95" r="3" fill="#cbd5e1" />
                    <circle cx="75" cy="95" r="3" fill="#cbd5e1" />
                </svg>
            </div>
        </div>

        <!-- Right Column: Interactive Cards & Mockups -->
        <div class="w-full lg:w-1/2 relative h-[520px] sm:h-[600px] flex items-center justify-center select-none">
            <!-- Golden curve line decoration in background -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none -z-10">
                <svg class="w-full h-full max-w-[450px]" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 60 280 Q 200 40 340 280" stroke="#fcd34d" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.6"/>
                </svg>
            </div>

            <!-- Floating Card: Weekly activity / Rent Collections -->
            <div class="absolute left-2 sm:left-6 top-16 sm:top-24 bg-white rounded-2xl p-5 sm:p-6 shadow-[0_20px_50px_rgba(240,244,248,0.7)] border border-slate-100 w-[260px] sm:w-[300px] z-20 animate-float-2 hover:scale-[1.02] transition-transform duration-300">
                <div class="text-sm sm:text-base font-bold text-slate-800 mb-5">Rent Collection Activity</div>
                <div class="flex items-end justify-between gap-2.5 h-24 mb-4">
                    <div class="flex-1 bg-uds-blue opacity-50 rounded-t-md hover:opacity-100 transition-opacity duration-200" style="height: 30%"></div>
                    <div class="flex-1 bg-uds-blue opacity-70 rounded-t-md hover:opacity-100 transition-opacity duration-200" style="height: 50%"></div>
                    <div class="flex-1 bg-uds-blue opacity-60 rounded-t-md hover:opacity-100 transition-opacity duration-200" style="height: 42%"></div>
                    <div class="flex-1 bg-uds-blue opacity-80 rounded-t-md hover:opacity-100 transition-opacity duration-200" style="height: 60%"></div>
                    <div class="flex-1 bg-uds-blue opacity-65 rounded-t-md hover:opacity-100 transition-opacity duration-200" style="height: 48%"></div>
                    <div class="flex-1 bg-uds-blue opacity-90 rounded-t-md hover:opacity-100 transition-opacity duration-200" style="height: 75%"></div>
                    <div class="flex-1 bg-uds-blue rounded-t-md hover:brightness-110 transition-all duration-200" style="height: 95%"></div>
                </div>
                <div class="flex justify-between text-[10px] font-semibold text-slate-400 px-1 mt-2">
                    <span>18/10</span>
                    <span>19/10</span>
                    <span>21/10</span>
                    <span>23/10</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-[11px] font-semibold text-slate-500 border-t border-slate-100 pt-3">
                    <span>Target: 15M RWF</span>
                    <span class="text-uds-blue">Collected: 14.2M RWF</span>
                </div>
            </div>

            <!-- Floating Card: Total Units -->
            <div class="absolute right-4 sm:right-8 top-4 sm:top-8 bg-white rounded-2xl p-5 sm:p-6 shadow-[0_20px_50px_rgba(240,244,248,0.7)] border border-slate-100 w-[200px] sm:w-[220px] z-30 animate-float-1 hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-uds-green" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Total Units</span>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800">15.9k</div>
                <div class="text-[10px] sm:text-xs font-bold text-emerald-500 mt-1 flex items-center gap-1">
                    <span>↑ 2.1%</span> <span class="text-slate-400 font-normal">vs last 7 days</span>
                </div>
                <!-- Visual Occupancy Rate Progress Bar -->
                <div class="mt-4 border-t border-slate-100 pt-3">
                    <div class="flex justify-between text-[11px] font-semibold text-slate-500 mb-1">
                        <span>Occupancy Rate</span>
                        <span>94.2%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-uds-green h-1.5 rounded-full" style="width: 94.2%"></div>
                    </div>
                </div>
            </div>

            <!-- Floating Card: Resolved Maintenance Requests -->
            <div class="absolute left-2 sm:left-4 bottom-8 sm:bottom-12 bg-white rounded-2xl p-5 shadow-[0_20px_50px_rgba(240,244,248,0.7)] border-2 border-uds-green w-[240px] sm:w-[260px] z-30 animate-float-3 hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-uds-green flex items-center justify-center text-white shadow-md shadow-uds-green/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xl sm:text-2xl font-extrabold text-slate-800">+ 2.938</span>
                </div>
                <div class="text-[11px] sm:text-xs text-slate-400 mt-2 font-semibold">Resolved Maintenance</div>
                <!-- Visual Checklist for resolved requests -->
                <div class="mt-3 space-y-1.5 border-t border-slate-100 pt-2.5">
                    <div class="flex items-center justify-between text-[10px] text-slate-600 font-medium">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-uds-green"></span>
                            Apt 4B: Leak repaired
                        </span>
                        <span class="text-emerald-500 bg-emerald-50 px-1 py-0.2 rounded text-[9px] font-bold">Done</span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-600 font-medium">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-uds-green"></span>
                            Bldg A: Lift serviced
                        </span>
                        <span class="text-emerald-500 bg-emerald-50 px-1 py-0.2 rounded text-[9px] font-bold">Done</span>
                    </div>
                </div>
            </div>

            <!-- Floating Card: Active Tenants -->
            <div class="absolute right-2 sm:right-4 bottom-16 sm:bottom-24 bg-white rounded-2xl p-5 sm:p-6 shadow-[0_20px_50px_rgba(240,244,248,0.7)] border border-slate-100 w-[240px] sm:w-[260px] z-20 animate-float-4 hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-uds-orange" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Active Tenants</span>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800">256.18k</div>
                <div class="text-[10px] sm:text-xs font-bold text-emerald-500 mt-1 flex items-center gap-1">
                    <span>↑ 2.1%</span> <span class="text-slate-400 font-normal">vs last 7 days</span>
                </div>
                <!-- Visual Tenant Avatar Pile -->
                <div class="flex items-center mt-3 -space-x-1.5 overflow-hidden border-t border-slate-100 pt-3">
                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-uds-blue text-[9px] font-bold text-white flex items-center justify-center">JD</div>
                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-uds-orange text-[9px] font-bold text-white flex items-center justify-center">AM</div>
                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-uds-green text-[9px] font-bold text-white flex items-center justify-center">TK</div>
                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-200 text-[8px] font-bold text-slate-500 flex items-center justify-center">+42</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="w-full text-center py-4 border-t border-slate-200/60 mt-auto relative z-10 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
        <p class="text-xs sm:text-sm text-slate-400 font-medium">
            &copy; {{ date('Y') }} My Business Growth. All rights reserved.
        </p>
        <span class="hidden sm:inline text-slate-300">|</span>
        <p class="text-xs sm:text-sm text-slate-400 font-medium">
            Powered by <span class="text-uds-blue font-semibold">Ubwenge Digital Solutions Ltd</span>
        </p>
    </footer>

    <!-- Livewire Request Modal -->
    @livewire('user.request.request-livewire')

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>

</html>
