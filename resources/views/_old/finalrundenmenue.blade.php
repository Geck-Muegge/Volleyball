
<!-- resources/views/finalspiel/finalrundemenue.blade.php -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spiele</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
    @include('partials.header') <!-- Kopfzeile einfügen -->

    <div class="container mt-5">
        <h1 class="mb-4">Finalrunde</h1>
        <div> <p></p></div>
        <form action="{{ route('finalspiel.generiereAF') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" {{ $achtelfinaleGeneriert ? 'disabled' : '' }}>Achtelfinale generieren</button>
        </form>
        <div> <p></p></div>
        <form action="{{ route('finalspiel.generiereTR') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" {{ $trostrundeGeneriert ? 'disabled' : '' }}>Trostrunde generieren</button>
        </form>
        <div> <p></p></div>
        @if(session('achtelfinale_generiert'))
            <form action="{{ route('finalspiel.generiereVF') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary"{{ $viertelfinaleGeneriert ? 'disabled' : '' }}>Viertelfinale generieren</button>
            </form>
        @endif
        <div> <p></p></div>
        @if(session('viertelfinale_generiert'))
            <form action="{{ route('finalspiel.generiereHF') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" {{ $halbfinaleGeneriert ? 'disabled' : '' }}>Halbfinale generieren</button>
            </form>
        @endif
        <div> <p></p></div>
        @if(session('halbfinale_generiert'))
            <form action="{{ route('finalspiel.generiereFF') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Finalspiele generieren</button>
            </form>
        @endif

    </div>

    <!-- Bootstrap JS und jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>

