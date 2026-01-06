<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            <div class="avatar-circle">
                <?php echo htmlspecialchars(strtoupper(substr($this->data['name'], 0, 2))); ?>
            </div>
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($this->data['name']); ?></h1>
            <p class="user-role">
                <?php if ($this->data['isAdmin']): ?>
                    <span class="badge badge-admin">Administrador</span>
                <?php else: ?>
                    <span class="badge badge-user">Usuario</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="profile-content">
        <section class="profile-section">
            <h2>Información Personal</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nombre de Usuario</label>
                    <p><?php echo htmlspecialchars($this->data['user']->Name); ?></p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p><?php echo htmlspecialchars($this->data['user']->Email); ?></p>
                </div>
                <div class="info-item">
                    <label>Teléfono</label>
                    <p><?php echo htmlspecialchars($this->data['user']->PhoneNumber); ?></p>
                </div>
            </div>
            <button class="btn-edit">Editar Información</button>
        </section>

        <section class="profile-section">
            <h2>Historial de Pedidos</h2>
            <div class="orders-list">
                <?php foreach ($this->data['orders'] as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-number">#ORD-<?php echo htmlspecialchars($order->OrderNumber) ?></span>
                            <span class="order-status status-<?php echo htmlspecialchars(strtolower($order->Status)) ?>"><?php echo htmlspecialchars(ucfirst($order->Status)) ?></span>
                        </div>
                        <div class="order-body">
                            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($order->CreatedAt->format('l, F j, Y H:i:s')) ?></p>
                            <p><strong>Total:</strong> <?php echo htmlspecialchars($order->Total) ?>€</p>
                            <p><strong>Productos:</strong>
                                <?php foreach ($order->Products as $product): ?>
                                <?php echo htmlspecialchars($product->Name) ?>
                                <?php endforeach; ?>
                            </p>
                        </div>
                        <div class="order-footer">
                            <button class="btn-secondary">Ver Detalles</button>
                            <button class="btn-primary">Volver a Comprar</button>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </section>
        
        <section class="profile-section">
            <h2>Configuración de Cuenta</h2>
            <div class="settings-list">
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Cambiar Contraseña</h3>
                        <p>Actualiza tu contraseña regularmente</p>
                    </div>
                    <button class="btn-secondary" onclick="document.getElementById('changePasswordModal').style.display='flex'">Cambiar</button>
                </div>
            </div>
        </section>
    </div>
</div>

<div id="changePasswordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Cambiar Contraseña</h2>
            <span class="close" onclick="document.getElementById('changePasswordModal').style.display='none'">&times;</span>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm">
                <div class="form-group">
                    <label for="oldPassword">Contraseña Actual</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Nueva Contraseña</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('changePasswordModal').style.display='none'">Cancelar</button>
                    <button type="button" class="btn-primary" onclick="ChangePass()">Guardar</button>
                </div>
                <div id="passwordChangeMessage" class="form-message">
                    <span class="message"></span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ChangePass() {
    var oldPass = document.getElementById('oldPassword').value;
    var newPass = document.getElementById('newPassword').value;
    var confirmPass = document.getElementById('confirmPassword').value;

    if (newPass !== confirmPass) {                
        return;
    }
    
    var data = 'oldPassword=' + encodeURIComponent(oldPass) + '&newPassword=' + encodeURIComponent(newPass);
    
    fetch('/user/profile', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: data
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            document.getElementById('changePasswordModal').style.display='none';
            document.getElementById('changePasswordForm').reset();
        } else {
            document.querySelector('#passwordChangeMessage .message').innerText = data.message;
            document.getElementById('passwordChangeMessage').style.display = 'block';
        }
    });
}

window.onclick = function(event) {
    var modal = document.getElementById('changePasswordModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
