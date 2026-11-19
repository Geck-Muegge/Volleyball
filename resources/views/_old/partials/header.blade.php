<!-- resources/views/partials/header.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('dashboard') }}">
        <img src="{{ asset('images\logo.PNG') }}" alt="Logo" style="height: 60px;">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Gruppenphase</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('finalboard.index') }}">Finalrunde</a>
            </li>
        </ul>
    </div>
</nav>
