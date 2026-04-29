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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/build/assets/app-DudOFP__.css') }}">
    <script src="{{ asset('assets/build/assets/app-eMHK6VFw.js') }}"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/img/logo/icon.png') }}">
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
    </style>
    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div class="font-sans antialiased text-gray-900">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>
