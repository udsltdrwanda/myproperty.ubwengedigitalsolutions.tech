<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Basic Meta Tags -->
    <meta property="og:title" content="Property Management System - Inception Report by RWASIBO AbdoulKarim">
    <meta property="og:description"
        content="A comprehensive inception report outlining the requirements, design, and implementation plan for an efficient property management system.">
    <meta property="og:image" content="{{ asset('assets/images/property-management-og.jpg') }}">
    <meta property="og:url" content="https://www.propertysystem.example.com">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Property Management System - Inception Report by RWASIBO AbdoulKarim">
    <meta name="twitter:description"
        content="A system designed to streamline property, tenant, lease, and payment management for property administrators and stakeholders.">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/img/logo/icon.png') }}">

    <!-- Load Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- App Styles -->
    <link rel="stylesheet" href="{{ asset('assets/build/assets/app-DspuE8pW.js') }}">
    <link rel="stylesheet" href="{{ asset('assets/build/assets/app-DudOFP__.css') }}">

    <!-- FontAwesome and Quill CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Load Flowbite JS in the head to ensure it's available immediately -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

    {{-- $this->resetValidation(); --}}

    <style>
        .btn-loading {
            position: relative !important;
            cursor: wait !important;
            pointer-events: none !important;
        }

        .btn-loading .btn-text {
            visibility: hidden !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 3px solid #fff;
            border-top: 3px solid #061077;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: spin 1s linear infinite;
            z-index: 10;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .floating-chat-btn {
            transition: transform 0.3s ease;
        }

        .floating-chat-btn:hover {
            transform: scale(1.1);
        }

        @media (max-width: 576px) {
            .modal-dialog-bottom-right {
                width: calc(100% - 40px);
                max-width: 300px;
            }
        }

        .nav-link:focus:not(:focus-visible) {
            outline: none;
        }

        .nav-link:focus-visible {
            outline: 2px solid blue;
        }

        /* UDS Brand Color Utilities */
        .bg-uds-blue { background-color: #003b70 !important; }
        .text-uds-blue { color: #003b70 !important; }
        .bg-uds-orange { background-color: #f39200 !important; }
        .text-uds-orange { color: #f39200 !important; }
        .text-uds-navy { color: #0b2545 !important; }
        .bg-uds-navy { background-color: #0b2545 !important; }

        .hover\:bg-uds-blue:hover { background-color: #003b70 !important; }
        .hover\:text-uds-blue:hover { color: #003b70 !important; }
        .hover\:bg-uds-orange:hover { background-color: #f39200 !important; }
        .hover\:text-uds-orange:hover { color: #f39200 !important; }
        .hover\:text-white:hover { color: #ffffff !important; }

        /* Responsive Fallback Utilities */
        @media (min-width: 768px) {
            .md\:flex-row { flex-direction: row !important; }
            .md\:items-center { align-items: center !important; }
            .md\:justify-between { justify-content: space-between !important; }
            .md\:block { display: block !important; }
            .md\:hidden { display: none !important; }
            .md\:flex { display: flex !important; }
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
            .md\:text-left { text-align: left !important; }
            .md\:text-right { text-align: right !important; }
        }
        @media (min-width: 1024px) {
            .lg\:flex-row { flex-direction: row !important; }
            .lg\:items-center { align-items: center !important; }
            .lg\:justify-between { justify-content: space-between !important; }
            .lg\:block { display: block !important; }
            .lg\:hidden { display: none !important; }
            .lg\:flex { display: flex !important; }
        }
    </style>

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')
        @include('layouts.auth.aside-menu')

        <!-- Page Content -->
        <main>
            <div class="p-4 sm:ml-64">
                <div class="border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    <!-- Form submission handling script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = this.querySelector('button[type="submit"]');

                    if (submitButton) {
                        if (submitButton.classList.contains('btn-loading')) {
                            e.preventDefault();
                            return;
                        }
                        if (!submitButton.querySelector('.btn-text')) {
                            const buttonText = submitButton.innerHTML;
                            submitButton.innerHTML = `<span class="btn-text">${buttonText}</span>`;
                        }
                        submitButton.classList.add('btn-loading');
                        submitButton.disabled = true;
                    }
                });
            });

            window.addEventListener('pageshow', function(event) {
                const buttons = document.querySelectorAll('.btn-loading');
                buttons.forEach(button => {
                    button.classList.remove('btn-loading');
                    button.disabled = false;
                    const btnText = button.querySelector('.btn-text');
                    if (btnText) {
                        button.innerHTML = btnText.textContent;
                    }
                });
            });
        });
    </script>

    <!-- Initialize Flowbite components and handle Livewire updates -->
    <script>
        function initializeFlowbite() {
            // Initialize Flowbite components
            if (typeof window.initFlowbite === 'function') {
                window.initFlowbite();
            } else if (typeof window.Flowbite !== 'undefined') {
                // Alternative approach if initFlowbite function is not available
                new window.Flowbite();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeFlowbite();
        });

        // Initialize when Livewire loads
        document.addEventListener('livewire:load', function() {
            initializeFlowbite();

            // Re-initialize after any Livewire update
            Livewire.hook('message.processed', () => {
                initializeFlowbite();
            });
        });
    </script>

    <!-- Quill editor initialization -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <!-- Select2 initialization -->
    <script>
        document.addEventListener('livewire:load', function() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2').select2();
            }
            Livewire.hook('message.processed', () => {
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('.select2').select2();
                }
            });
        });
    </script>

    <!-- Flash message handling -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Success message listener
            Livewire.on('show-success-message', (event) => {
                const successMessage = document.createElement('div');
                successMessage.id = 'success-message';
                successMessage.className =
                    'fixed top-4 right-4 p-4 text-green-700 bg-green-100 border border-green-400 rounded shadow-lg alert alert-success z-50';
                successMessage.style.right = '16px'; // Ensure right alignment
                successMessage.innerHTML = `${event.message}
                    <div class="absolute bottom-0 left-0 h-1 bg-green-400 time-indicator"></div>`;
                document.body.appendChild(successMessage);
                setTimeout(() => {
                    successMessage.remove();
                }, 10000);
            });

            // Error message listener
            Livewire.on('show-error-message', (event) => {
                const errorMessage = document.createElement('div');
                errorMessage.id = 'error-message';
                errorMessage.className =
                    'fixed top-4 right-4 p-4 text-red-700 bg-red-100 border border-red-400 rounded shadow-lg alert alert-danger z-50';
                errorMessage.style.right = '16px'; // Ensure right alignment
                errorMessage.innerHTML = `${event.message}
                    <div class="absolute bottom-0 left-0 h-1 bg-red-400 time-indicator"></div>`;
                document.body.appendChild(errorMessage);
                setTimeout(() => {
                    errorMessage.remove();
                }, 10000);
            });
        });
    </script>
</body>

</html>
