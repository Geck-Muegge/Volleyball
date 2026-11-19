
<!-- resources/views/spiele/alle.blade.php: zeigt die nächsten zehn nicht eingetragenen Spiele an -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spiele</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <!-- Eigene CSS-Datei -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    
    <div class="container mt-5">
        <h1 class="mb-4">Die nächsten Spiele</h1>
        
        <!-- Tabelle für die Spiele -->
        <table class="table ">
            <thead>
                <tr>
                    <th>Spiel</th>
                    <th>Feld</th>
                    <th>TeamA</th>
                    <th>TeamB</th>
                    <th>Schiedsrichter</th>
                    <th>Punkte A</th>
                    <th>Punkte B</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($gruppenspiele as $spiel)
                    <tr class="{{ $spiel->runde == 'grA' ? 'gruppe-a' : ($spiel->runde == 'grB' ? 'gruppe-b' : ($spiel->runde == 'grC' ? 'gruppe-c': 'gruppe-d')) }}">
                        <td>{{ $spiel->spielid}}</td>
                        <td>{{ $spiel->feld }}</td>
                        <td class="{{ session('winner') == 'teamA' && session('spiel')->spielid == $spiel->spielid ? 'table-danger' : '' }}">{{ $spiel->teamA }}</td>
                        <td class="{{ session('winner') == 'teamB' && session('spiel')->spielid == $spiel->spielid ? 'table-danger' : '' }}">{{ $spiel->teamB }}</td>                      
                        <td>{{ $spiel->schiedsrichter }}</td>
                        <td>{{ $spiel->punkteA }}</td>
                        <td>{{ $spiel->punkteB }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS und jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>

