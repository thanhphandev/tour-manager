@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - Tour Management System</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ 
    sidebarOpen: true, 
    mobileMenuOpen: false,
    darkMode: false,
    notifications: false,
    profile: false
}">
    <div class="h-screen">
        {{ $slot }}
    </div>
</body>
</html>