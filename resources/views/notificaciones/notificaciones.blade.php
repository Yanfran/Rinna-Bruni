<script>
    const pusher = new Pusher('6dd5506f3b17075578e1', {
        cluster: 'us2',
        encrypted: true
    });

    // Suscríbete a un canal
    const channel = pusher.subscribe('notifications');

    // Escucha los eventos del canal
    channel.bind('new-notification', function(data) {
        getNotifications();
    });

    // Función para obtener las notificaciones
    function getNotifications() {
        axios.get('/notifications/all')
            .then(function(response) {
                var notifications = response.data.notifications;
                var notificationList = document.getElementById('notification-list');
                var notificationCount = document.getElementById('notification-count');

                // Limpiar la lista de notificaciones
                notificationList.innerHTML = '';

                // Verificar si notifications es un array válido
                if (Array.isArray(notifications)) {
                    notifications.forEach(function(notification) {
                        var li = document.createElement('li');
                        li.innerHTML = '<span class="titulo-pedido">' + notification.titulo + '</span>: ' +
                            notification.mensaje;
                        notificationList.appendChild(li);
                    });

                    // Actualizar el número de notificaciones en el badge
                    notificationCount.textContent = response.data.count;
                } else {
                    console.error('La respuesta no contiene una matriz de notificaciones válida');
                }
            })
            .catch(function(error) {
                console.error('Error al obtener las notificaciones:', error);
            });
    }

    // Cargar las notificaciones al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        getNotifications();
    });

    // Marcar notificaciones como leídas
    function marcarNotificacionesLeidas() {
        axios.post('/notifications/mark-as-read')
            .then(function(response) {
                // Actualizar el número de notificaciones en el badge
                var notificationCount = document.getElementById('notification-count');
                notificationCount.textContent = 0; // Establecer el contador en cero
            })
            .catch(function(error) {
                console.error('Error al marcar las notificaciones como leídas:', error);
            });
    }

    // Escuchar el evento de clic en el enlace de notificaciones
    var dropdownToggle = document.querySelector('.dropdown-toggle');
    dropdownToggle.addEventListener('click', function(event) {
        marcarNotificacionesLeidas();
    });
</script>
