<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abgemeldet</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="logout-container">
        <div class="icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Erfolgreich abgemeldet</h1>
        <p>Du wurdest sicher abgemeldet. Bis bald!</p>

        <div class="button-group">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Erneut anmelden
            </a>
            
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Hinweis:</strong> Deine Sitzung wurde beendet. Für Sicherheit werden alle Daten gelöscht.
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
