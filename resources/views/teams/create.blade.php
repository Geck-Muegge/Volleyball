<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teamanmeldung</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1D68AC 0%, #0d3d6b 100%);
            min-height: 100vh;
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #1D68AC;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1D68AC;
            box-shadow: 0 0 0 3px rgba(29, 104, 172, 0.1);
        }

        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
            background: white;
        }

        .form-group select:focus {
            outline: none;
            border-color: #1D68AC;
            box-shadow: 0 0 0 3px rgba(29, 104, 172, 0.1);
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1D68AC 0%, #0d3d6b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(29, 104, 172, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .error-list {
            margin: 0;
            padding-left: 20px;
        }

        .error-list li {
            margin-bottom: 5px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #1D68AC;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #0d3d6b;
            text-decoration: underline;
        }

        .icon {
            font-size: 40px;
            color: #1D68AC;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <div class="icon">
                <i class="fas fa-volleyball-ball"></i>
            </div>
            <h1>Teamanmeldung</h1>
            <p>Melde dein Team für das Volleyball-Turnier an</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-circle"></i> Fehler bei der Anmeldung:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teams.store') }}">
            @csrf

            <div class="form-group">
                <label for="teamName">
                    <i class="fas fa-users"></i> Teamname
                </label>
                <input
                    id="teamName"
                    name="teamName"
                    type="text"
                    placeholder="z.B. Klasse 10a"
                    value="{{ old('teamName') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="klasse">
                    <i class="fas fa-graduation-cap"></i> Klasse
                </label>
                <input
                    id="klasse"
                    name="klasse"
                    type="text"
                    placeholder="z.B. 10a"
                    value="{{ old('klasse') }}"
                    required
                >
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Team anmelden
            </button>
        </form>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
