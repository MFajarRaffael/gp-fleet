
self.addEventListener('push', function (event) {
    let data = {};

    try {
        data = event.data ? event.data.json() : {};
    } catch (error) {
        data = {
            title: 'GP Fleet Management',
            body: 'Ada aktivitas baru di sistem. Buka GP Fleet untuk melihat informasi lebih lanjut.'
        };
    }

    const title = data.title || 'GP Fleet Management';

    const options = {
        body: data.body || 'Ada aktivitas baru di sistem. Buka GP Fleet untuk melihat informasi lebih lanjut.',
        icon: '/images/gpi_logo.png',
        badge: '/images/gpi_logo.png',
        data: {
            url: '/riwayat-aktivitas'
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    event.waitUntil(
        clients.openWindow(event.notification.data?.url || '/dashboard')
    );
});
