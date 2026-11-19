<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Volleyball-Turnier')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/teams.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .navbar-custom .navbar-brand {
            color: #1D68AC !important;
            font-weight: 700;
            font-size: 20px;
        }

        .navbar-custom .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #1D68AC !important;
        }

        .navbar-custom .nav-link.active {
            color: #1D68AC !important;
            border-bottom: 3px solid #1D68AC;
        }

        .user-status {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 15px;
            border-left: 2px solid #e0e0e0;
        }

        .user-info {
            font-size: 13px;
            color: #666;
        }

        .user-info strong {
            color: #1D68AC;
            display: block;
        }

        .logout-btn-nav {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 13px;
        }

        .logout-btn-nav:hover {
            background: #c82333;
        }

        @media (max-width: 768px) {
            .user-status {
                border-left: none;
                border-top: 2px solid #e0e0e0;
                padding-left: 0;
                padding-top: 15px;
                margin-top: 15px;
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar-custom .nav-link {
                margin: 5px 0;
            }
        }
    </style>
    @yield('extra-css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-main">
            <a class="navbar-brand" href="{{ route('teams.index') }}">
                <i class="fas fa-volleyball-ball"></i> Volleyball-Turnier ATIW
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teams.index') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                                <i class="fas fa-users"></i> Teams
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('spiele.index') ? 'active' : '' }}" href="{{ route('spiele.index') }}">
                                <i class="fas fa-gamepad"></i> Gruppenspiele
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('spiele.tabellengruppe') ? 'active' : '' }}" href="{{ route('spiele.tabellengruppe', ['gruppenid' => 'all']) }}">
                                <i class="fas fa-chart-bar"></i> Tabellen
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('finalspiel.index') ? 'active' : '' }}" href="{{ route('finalspiel.index') }}">
                                <i class="fas fa-trophy"></i> Finalrunde
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <div class="user-status">
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <strong>{{ auth()->user()->name }}</strong>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="logout-btn-nav">
                                        <i class="fas fa-sign-out-alt"></i> Abmelden
                                    </button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Anmelden
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
</body>
</html>
