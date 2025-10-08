<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ trim(($title ?? '') . ' - ShareTracker', ' - ') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css','resources/css/layout.css','resources/js/app.js'])
    @stack('styles')
</head>
<body>
<nav class="navbar">
    <div class="navbar-content">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-chart-line"></i></div>
            <h1>ShareTracker</h1>
        </div>
        <div class="navbar-right">
            @auth
            <div class="user-info">
                <i class="fas fa-user-circle user-icon"></i>
                <span>{{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Logout?')">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </form>
            @endauth
            @guest
            <a href="{{ route('login') }}" class="btn-logout link-flex"><i class="fas fa-sign-in-alt"></i> <span>Login</span></a>
            @endguest
        </div>
    </div>
</nav>
<main class="app-container">
    {{ $slot ?? '' }}
    @yield('content')
</main>
@stack('modals')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
