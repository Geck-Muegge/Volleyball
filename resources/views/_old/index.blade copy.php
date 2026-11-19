<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Startoberfläche</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <style>
        .logo {
            max-width: 300px; /* Setze die maximale Breite des Logos */
            height: auto; /* Höhe automatisch anpassen */           
        }
    </style>
</head>
<body>
    @include('partials.header') <!-- Kopfzeile einfügen -->
    <div class="container mt-5 text-center">
        <h1>Verwaltung der Finalrunde</h1>
        <p> </p>
        <img src="{{ asset('images\logo.PNG') }}" alt="Logo" class="logo mb-4">
        <p> </p>
        <h4>Wählen Sie eine der folgenden Optionen:</h4>
        
        <div class="list-group">
            <a href="{{ route('finalspiel.index') }}" class="list-group-item list-group-item-action">Finalspiele anzeigen und Ergebnisse eintragen</a>
            <a href="{{ route('finalspiel.zeigeMenue') }}" class="list-group-item list-group-item-action">Finalrunde erzeugen</a>
            <a href="{{ route('finalspiel.index') }}" class="list-group-item list-group-item-action">Abschlusstabelle</a>           
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
