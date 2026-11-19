@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="container-main" style="padding-top: 40px;">
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
    @if ($unassignedTeams->count() > 0)
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; color: #1D68AC;"><i class="fas fa-inbox"></i> Unzugeordnete Teams</h3>
            </div>
            <a href="{{ route('teams.create') }}" style="display: block; background: #1D68AC; color: white; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center; margin-bottom: 20px; transition: background 0.3s;">
                <i class="fas fa-plus"></i> Team anmelden
            </a>
            <div class="unassigned-section" style="margin-top: 0;">
                <p class="text-muted" style="font-size: 12px; margin-bottom: 15px;">Ziehe Teams in eine Gruppe.</p>
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

    <div>
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            @auth
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; gap: 10px; padding-top: 10px;">
                    <a href="{{ route('teams.create') }}" style="background: #1D68AC; color: white; padding: 12px 14px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.3s; display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px;">
                        <i class="fas fa-plus" style="font-size: 18px;"></i>
                    </a>
                    <span style="color: #999; font-size: 11px; text-align: center; width: 45px;">Team<br>anmelden</span>
                </div>
            @endauth

            <div class="groups-container" style="flex: 1;">
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



@section('extra-js')
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
@endsection
@endsection
