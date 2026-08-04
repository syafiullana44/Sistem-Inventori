// ============================================
// NOTIFIKASI REAL-TIME
// ============================================
function fetchNotifikasi() {
    fetch(window.AppConfig.notifikasiUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotifikasi(data);
            }
        })
        .catch(error => console.error('Error fetching notifikasi:', error));
}

function updateNotifikasi(data) {
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');

    if (data.count > 0) {
        badge.textContent = data.count;
        badge.style.display = 'block';

        let html = '';
        data.data.forEach(item => {
            const colorMap = {
                'danger': '#dc2626',
                'warning': '#f59e0b',
                'info': '#3b82f6',
                'success': '#22c55e',
            };
            const bgColor = colorMap[item.color] || '#6b7280';
            html += `
                <a href="${item.link}" class="notification-item">
                    <div class="notif-icon" style="background: ${bgColor}20; color: ${bgColor};">
                        <i class="fas ${item.icon}"></i>
                    </div>
                    <div class="notif-text">
                        <div class="title">${item.message}</div>
                    </div>
                    <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>
            `;
        });
        list.innerHTML = html;
    } else {
        badge.style.display = 'none';
        list.innerHTML = `
            <div class="text-center text-muted py-4" style="font-size: 13px;">
                <i class="fas fa-check-circle me-1"></i> Tidak ada notifikasi
            </div>
        `;
    }
}

// Toggle dropdown notifikasi
document.getElementById('notificationBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    dropdown.classList.add('fade-in');
});

// Tutup dropdown saat klik di luar
document.addEventListener('click', function(e) {
    if (!e.target.closest('#notificationContainer')) {
        document.getElementById('notificationDropdown').style.display = 'none';
    }
});

// ============================================
// UPDATE JAM REAL-TIME
// ============================================
function updateClock() {
    const now = new Date();
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
}

// ============================================
// START SERVICES
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Notifikasi - setiap 15 detik
    fetchNotifikasi();
    setInterval(fetchNotifikasi, 15000);

    // Jam - setiap 60 detik
    setInterval(updateClock, 60000);
});
