// js/reports.js
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initReportsListeners);
} else {
    initReportsListeners();
}

function initReportsListeners() {
    const navItem = document.querySelector('[data-target="reports-view"]');
    if (navItem) {
        navItem.addEventListener('click', loadReports);
    }
}

async function loadReports() {
    try {
        const response = await fetch('api/reports.php');
        const data = await response.json();
        if (data.status === 'success') {
            renderReports(data.data);
        }
    } catch (err) {
        console.error("Échec du chargement des rapports :", err);
    }
}

function renderReports(logs) {
    const tbody = document.querySelector('#reportsTable tbody');
    if (!tbody) return;

    if (logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Aucune activité enregistrée.</td></tr>';
        return;
    }

    tbody.innerHTML = logs.map(log => {
        const date = new Date(log.timestamp).toLocaleString('fr-FR');
        return `
            <tr>
                <td>${date}</td>
                <td>
                    <span class="user-badge ${log.role}">
                        ${log.first_name} ${log.last_name}
                    </span>
                </td>
                <td><strong>${log.action}</strong></td>
                <td><small>${log.details || ''}</small></td>
            </tr>
        `;
    }).join('');
}
