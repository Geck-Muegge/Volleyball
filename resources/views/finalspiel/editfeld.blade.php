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

        <form method="POST" action="{{ route('finalspiel.updatefeld', $spiel->spielid) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                 <!-- Anzeigen des Teamnamens für Team A -->
                <label for="feld">Feld:</label>
                <input type="number" name="feld" id="feld" class="form-control" value="{{ $spiel->feld }}" required min="1" max="4" step="1">
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
