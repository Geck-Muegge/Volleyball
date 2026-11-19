<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lehrkraft-Anmeldung</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <div class="icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1>Lehrkraft-Anmeldung</h1>
            <p>Melde dich an, um Teams zu verwalten</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-circle"></i> Anmeldung fehlgeschlagen</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> E-Mail
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="deine.email@schule.de"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-key"></i> Passwort
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i> Anmelden
            </button>
        </form>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Hinweis:</strong> Nur Lehrkräfte mit gültigen Zugangsdaten können sich anmelden.
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

