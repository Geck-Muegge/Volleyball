<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gruppenauswahl</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
    <div class="container mt-5">
        <h1>Wählen Sie eine Gruppe</h1>

        <!-- Formular zur Gruppenauswahl -->

        <form>
            <div class="form-group">
                <label for="gruppenid">Wählen Sie eine Gruppe:</label>
                <select name="gruppenid" id="gruppenid" class="form-control" onchange="location = this.value;">
                    <option value="">-- Bitte wählen --</option>
                    @foreach ($spielgruppen as $gruppe)
                        <option value="{{ route('spiele.index1', $gruppe->gruppenBez) }}">
                            {{ $gruppe->gruppenBez }} <!-- Hier verwenden wir gruppenbez -->
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
