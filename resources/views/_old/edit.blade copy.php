<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spiel bearbeiten</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
    <div class="container mt-5">
        <h1>Spiel bearbeiten</h1>

        <form method="POST" action="{{ route('finalspiel.update', $spiel->spielid) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                 <!-- Anzeigen des Teamnamens für Team A -->
                <label for="punkteA">Punkte: {{ $spiel->teamA }}</label>
                <input type="number" name="punkteA" id="punkteA" class="form-control" value="{{ $spiel->punkteA }}" required min="0" max="50" step="1">
            </div>

            <div class="form-group">
                 <!-- Anzeigen des Teamnamens für Team B -->
                <label for="punkteB">Punkte {{ $spiel->teamB }}:</label>
                <input type="number" name="punkteB" id="punkteB" class="form-control" value="{{ $spiel->punkteB }}" required min="0" max="50" step="1">
            </div>

            <button type="submit" class="btn btn-primary">Speichern</button>
            <a href="{{ route('finalspiel.index') }}" class="btn btn-secondary">Abbrechen</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
