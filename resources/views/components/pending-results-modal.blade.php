<!-- Modal für ausstehende Ergebnisse -->
<div id="pendingResultsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 30px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);">
        <h2 style="margin-top: 0; margin-bottom: 20px; color: #1D68AC;">
            <i class="fas fa-clock"></i> Ausstehende Ergebnisse
        </h2>
        <div id="resultsList"></div>
        <button onclick="closePendingResultsModal()" style="width: 100%; padding: 12px; background: #e0e0e0; color: #333; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; margin-top: 20px;">
            Schließen
        </button>
    </div>
</div>

<script>
    function approveExecution(spielid) {
        fetch('{{ route("admin.approve-result") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ spielid: spielid })
        })
        .then(() => {
            if (window.location.pathname.includes('/spiele')) {
                setTimeout(() => location.reload(), 1000);
            } else {
                loadPendingResults();
            }
        })
        .catch(error => console.error('Fehler:', error));
    }

    function rejectExecution(spielid) {
        fetch('{{ route("admin.reject-result") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ spielid: spielid })
        })
        .then(() => {
            if (window.location.pathname.includes('/spiele')) {
                setTimeout(() => location.reload(), 1000);
            } else {
                loadPendingResults();
            }
        })
        .catch(error => console.error('Fehler:', error));
    }

    let pollInterval;

    function loadPendingResults() {
        fetch('/api/pending-results')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    showResultModal(data);
                } else {
                    closePendingResultsModal();
                }
            })
            .catch(error => console.error('Fehler:', error));
    }

    function showResultModal(results) {
        let html = '';
        results.forEach(result => {
            html += `
                <div data-spielid="${result.spielid}" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                    <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 15px; align-items: center; margin-bottom: 15px;">
                        <div style="text-align: center; font-weight: 700;">${result.teamA}</div>
                        <div style="text-align: center; font-size: 24px; font-weight: 700; color: #FF6B6B;">${result.punkteA} : ${result.punkteB}</div>
                        <div style="text-align: center; font-weight: 700;">${result.teamB}</div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="approveExecution(${result.spielid})" style="flex: 1; padding: 10px; background: #4ECDC4; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 700;">✓ Bestätigen</button>
                        <button onclick="rejectExecution(${result.spielid})" style="flex: 1; padding: 10px; background: #FF6B6B; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 700;">✗ Ablehnen</button>
                    </div>
                </div>
            `;
        });
        document.getElementById('resultsList').innerHTML = html;
        document.getElementById('pendingResultsModal').style.display = 'flex';
    }

    function closePendingResultsModal() {
        document.getElementById('pendingResultsModal').style.display = 'none';
    }

    loadPendingResults();
    pollInterval = setInterval(loadPendingResults, 5000);
</script>
