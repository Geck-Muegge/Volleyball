<!-- resources/views/teams/index.blade.php -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teamliste</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/teams.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="container-main">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0;"><i class="fas fa-volleyball-ball"></i> Teamliste Volleyballturnier ATIW</h1>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ route('teams.create') }}" style="background: #1D68AC; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 14px; transition: background 0.3s;">
                        <i class="fas fa-plus"></i> Team anmelden
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn" style="padding: 8px 14px; font-size: 14px;">
                            <i class="fas fa-sign-out-alt"></i> Abmelden
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-main">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> Es gab einen Fehler:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="layout-wrapper" style="grid-template-columns: {{ $unassignedTeams->count() > 0 ? '280px 1fr' : '1fr' }};">
                <!-- Linke Seite: Unzugeordnete Teams -->
                @if ($unassignedTeams->count() > 0)
                    <div>
                        <div class="unassigned-section">
                            <h3><i class="fas fa-inbox"></i> Unzugeordnete Teams</h3>
                            <p class="text-muted" style="font-size: 12px;">Ziehe Teams in eine Gruppe.</p>
                            <div id="unassigned-list" style="display: flex; flex-direction: column; gap: 10px;">
                                @foreach ($unassignedTeams as $team)
                                    <div class="team-item" draggable="true" data-team="{{ $team->teamName }}">
                                        <div class="team-name">{{ $team->teamName }}</div>
                                        <div class="team-class">{{ $team->klasse }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Rechte Seite: Gruppen -->
                <div>
                    <div class="groups-container">
                        @foreach ($spielgruppen as $gruppe)
                            <div class="group-card">
                                <div class="group-header gruppe-{{ strtolower($gruppe->gruppenBez) }}">
                                    Gruppe {{ $gruppe->gruppenBez }}
                                </div>
                                <div class="team-list" id="group-{{ $gruppe->gruppenBez }}" data-gruppe="{{ $gruppe->gruppenBez }}">
                                    @if ($teamsByGruppe[$gruppe->gruppenBez]->count() > 0)
                                        @foreach ($teamsByGruppe[$gruppe->gruppenBez] as $team)
                                            <div class="team-item" draggable="true" data-team="{{ $team->teamName }}">
                                                <div class="team-name">{{ $team->teamName }}</div>
                                                <div class="team-class">{{ $team->klasse }}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="empty-message">Keine Teams zugeordnet</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let draggedElement = null;

        document.querySelectorAll('.team-item').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                draggedElement = item;
                item.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });

            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
            });
        });

        document.querySelectorAll('.team-list, #unassigned-list').forEach(list => {
            list.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                list.style.backgroundColor = 'rgba(29, 104, 172, 0.1)';
            });

            list.addEventListener('dragleave', () => {
                list.style.backgroundColor = '';
            });

            list.addEventListener('drop', (e) => {
                e.preventDefault();
                list.style.backgroundColor = '';

                if (draggedElement) {
                    const teamName = draggedElement.dataset.team;
                    const targetGruppe = list.dataset.gruppe || null;

                    list.appendChild(draggedElement);

                    const emptyMsg = list.querySelector('.empty-message');
                    if (emptyMsg) emptyMsg.remove();

                    fetch(`/teams/${teamName}/position`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            gruppenid: targetGruppe,
                            nummer: null
                        })
                    })
                    .catch(err => console.error('Fehler beim Speichern:', err));
                }
            });
        });
    </script>
</body>
</html>
