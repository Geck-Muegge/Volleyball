
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spielstandsanzeige</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="margin: 0; padding: 0;">

@section('title', 'Spielstandsanzeige')

@section('content')
<div style="height: 100vh; display: flex; flex-direction: column; background: linear-gradient(135deg, #1D68AC 0%, #0F4C8B 100%); padding: 40px; font-family: Arial, sans-serif; overflow: hidden;">
    <!-- Header -->
    <div style="text-align: center; color: white; margin-bottom: 40px;">
        <h1 style="font-size: 48px; font-weight: 700; margin: 0;">
            <i class="fas fa-volleyball-ball"></i> SPIELSTANDSANZEIGE
        </h1>
    </div>
    <!-- Main Score Display -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center;">

        <div style="background: white; border-radius: 30px; padding: 60px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4); width: 100%; max-width: 1200px;">

            <!-- Runde Info -->
            <div style="text-align: center; margin-bottom: 40px;">
                <p id="rundeText" style="font-size: 32px; font-weight: 700; color: #1D68AC; margin: 0;">
                    {{ $spiel->runde ?? 'Spiel' }}
                </p>
            </div>

            <!-- Teams & Score -->
            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 60px; align-items: center;">

                <!-- Team A -->
                <div style="text-align: center;">
                    <div id="teamA" style="font-size: 56px; font-weight: 700; color: #1D68AC; margin-bottom: 40px; line-height: 1.3;">
                        {{ $spiel->teamA ?? $spiel->teamAFK->teamName ?? 'Team A' }}
                    </div>
                    <div id="scoreA" style="font-size: 140px; font-weight: 700; color: #FF6B6B; line-height: 1;">
                        {{ $spiel->punkteA ?? 0 }}
                    </div>
                </div>

                <!-- VS -->
                <div style="text-align: center;">
                    <div style="font-size: 80px; color: #ccc; font-weight: 700; margin-bottom: 20px;">:</div>
                    <div style="font-size: 24px; color: #999; font-weight: 600;">VS</div>
                </div>

                <!-- Team B -->
                <div style="text-align: center;">
                    <div id="teamB" style="font-size: 56px; font-weight: 700; color: #1D68AC; margin-bottom: 40px; line-height: 1.3;">
                        {{ $spiel->teamB ?? $spiel->teamBFK->teamName ?? 'Team B' }}
                    </div>
                    <div id="scoreB" style="font-size: 140px; font-weight: 700; color: #4ECDC4; line-height: 1;">
                        {{ $spiel->punkteB ?? 0 }}
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div style="text-align: center; margin-top: 50px;">
                <p id="statusText" style="font-size: 18px; color: #999; margin: 0;">
                    Aktualisiert: <span id="lastUpdate">gerade eben</span>
                </p>
            </div>
        </div>
    </div>

</div>

<script>
    const spielid = {{ $spiel->spielid }};

    function updateScoreboard() {
        fetch(`/api/current-game/${spielid}`)
            .then(response => response.json())
            .then(data => {
                if (data.spiel) {
                    const spiel = data.spiel;

                    document.getElementById('teamA').textContent = spiel.teamA;
                    document.getElementById('teamB').textContent = spiel.teamB;

                    animateScore('scoreA', parseInt(document.getElementById('scoreA').textContent), spiel.punkteA);
                    animateScore('scoreB', parseInt(document.getElementById('scoreB').textContent), spiel.punkteB);

                    document.getElementById('rundeText').textContent = spiel.runde;

                    updateTimestamp();
                }
            })
            .catch(error => {
                console.error('Fehler beim Abrufen:', error);
            });
    }

    function animateScore(elementId, oldScore, newScore) {
        const element = document.getElementById(elementId);

        if (oldScore !== newScore) {
            element.style.transition = 'transform 0.3s ease, color 0.3s ease';
            element.style.transform = 'scale(1.2)';
            element.style.color = elementId === 'scoreA' ? '#FF6B6B' : '#4ECDC4';

            setTimeout(() => {
                element.textContent = newScore;
                element.style.transform = 'scale(1)';
            }, 150);
        } else {
            element.textContent = newScore;
        }
    }

    function updateTimestamp() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        document.getElementById('lastUpdate').textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Initial load
    updateScoreboard();

    // Update alle 500ms (0,5 Sekunden)
    setInterval(updateScoreboard, 500);

    // Update Timestamp jede Sekunde
    setInterval(updateTimestamp, 1000);
</script>

<style>
    @media (max-width: 1200px) {
        div[style*="grid-template-columns: 1fr auto 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 30px !important;
        }

        div[style*="font-size: 140px"] {
            font-size: 100px !important;
        }

        div[style*="font-size: 56px"] {
            font-size: 40px !important;
        }

        div[style*="font-size: 80px"] {
            font-size: 60px !important;
        }
    }
</style>
</body>
</html>
