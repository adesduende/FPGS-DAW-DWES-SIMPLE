<div class="admin-container">
    <h1 style="text-align: center; color: #333; margin-bottom: 2rem; font-size: 2.5rem;">Gestión de Usuarios</h1>

    <nav class="admin-nav">
        <ul>
            <li><a href="/admin/users">Usuarios</a></li>
            <li><a href="/admin/products">Productos</a></li>
            <li><a href="/admin/orders">Pedidos</a></li>
        </ul>
    </nav>

    <main class="admin-main">
        <div class="stats">
            <div class="stat-card">
                <h3>Total Usuarios</h3>
                <p class="stat-number"><?= htmlspecialchars($this->data['totalUsersCount']) ?></p>
            </div>
            <div class="stat-card">
                <h3>Usuarios Desactivados</h3>
                <p class="stat-number"><?= htmlspecialchars($this->data['activeUsersCount']) ?></p>
            </div>
            <div class="stat-card">
                <h3>Administradores</h3>
                <p class="stat-number"><?= htmlspecialchars($this->data['adminUsersCount']) ?></p>
            </div>
        </div>

        <div class="table-section">

            <div class="table-filters">
                <input id="searchInput" type="text" placeholder="Buscar por nombre o email..." class="search-input">
                <select id="roleFilter" class="filter-select">
                    <option value="all">Todos los roles</option>
                    <option value="admin">Administradores</option>
                    <option value="user">Usuarios</option>
                </select>
                <select id="statusFilter" class="filter-select">
                    <option value="all">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->data['totalUsers'] as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user->Name) ?></td>
                                <td><?= htmlspecialchars($user->Surname) ?></td>
                                <td><?= htmlspecialchars($user->Email) ?></td>
                                <td>
                                    <?php if ($user->Role === 'admin'): ?>
                                        <span class="role-admin">Administrador</span>
                                    <?php else: ?>
                                        <span class="role-user">Usuario</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user->IsActive): ?>
                                        <span class="status-active">Activo</span>
                                    <?php else: ?>
                                        <span class="status-inactive">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <button class="btn-edit action-btn" 
                                        onclick="openEditModal('<?= htmlspecialchars($user->Id->Id) ?>', '<?= htmlspecialchars($user->Name) ?>', '<?= htmlspecialchars($user->Surname) ?>', '<?= htmlspecialchars($user->Role) ?>')">✏️</button>
                                    <?php if ($user->IsActive): ?>
                                        <button class="action-btn-deactivate action-btn"
                                            onclick="UpdateUser(<?php echo '0'; ?>, '<?php echo htmlspecialchars($user->Id->Id); ?>')">🚫</button>
                                    <?php else: ?>
                                        <span class="action-btn-activate action-btn"
                                            onclick="UpdateUser(<?php echo '1'; ?>, '<?php echo htmlspecialchars($user->Id->Id); ?>')">✅</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <button class="page-btn" <?php echo $this->data['currentPage'] <= 1 ? 'disabled' : ''; ?>
                    onclick="NavigateToPage(<?php echo $this->data['currentPage'] - 1 > 0 ? $this->data['currentPage'] - 1 : 1; ?>)">«</button>
                <?php for ($i = 1; $i <= $this->data['totalPages']; $i++): ?>
                    <button class="page-btn <?php echo $i === $this->data['currentPage'] ? 'active' : '' ?>"
                        onclick="NavigateToPage(<?php echo htmlspecialchars($i); ?>)"><?= htmlspecialchars($i) ?></button>
                <?php endfor; ?>
                <button class="page-btn" <?php echo $this->data['currentPage'] >= $this->data['totalPages'] ? 'disabled' : ''; ?>
                    onclick="NavigateToPage(<?php echo $this->data['currentPage'] + 1 < $this->data['totalPages'] ? $this->data['currentPage'] + 1 : $this->data['totalPages']; ?>)">»</button>
            </div>
        </div>
    </main>

</div>

<!-- Edit User Role Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Rol de Usuario</h2>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="userId">
                
                <div class="form-group">
                    <label class="form-label">Usuario:</label>
                    <div class="user-info" id="editUserInfo"></div>
                </div>

                <div class="form-group">
                    <label for="editUserRole" class="form-label">Rol:</label>
                    <select id="editUserRole" name="role" class="form-select" required>
                        <option value="user">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function NavigateToPage(page) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('page', page);
        window.location.search = urlParams.toString();
    }
    function UpdateUser(isActive, userId) {
        data = new URLSearchParams({ isActive: isActive, userId: userId });
        fetch('/admin/users/activate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success)
                window.location.reload();
        });
    }

    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('.admin-table tbody tr');

    // Method to filter the table
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;
        const selectedStatus = statusFilter.value;

        tableRows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const surname = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const role = row.cells[3].textContent.toLowerCase();
            const status = row.cells[4].textContent.toLowerCase();

            const matchesSearch = name.includes(searchTerm) ||
                surname.includes(searchTerm) ||
                email.includes(searchTerm);

            const matchesRole = selectedRole === 'all' ||
                (selectedRole === 'admin' && role.includes('administrador')) ||
                (selectedRole === 'user' && role.includes('usuario'));

            const matchesStatus = selectedStatus === 'all' ||
                (selectedStatus === 'activo' && status.includes('activo')) ||
                (selectedStatus === 'inactivo' && status.includes('inactivo'));

            if (matchesSearch && matchesRole && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Add event listeners
    searchInput.addEventListener('keyup', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Modal Functions
    function openEditModal(userId, userName, userSurname, userRole) {
        document.getElementById('editUserId').value = userId;
        document.getElementById('editUserInfo').textContent = `${userName} ${userSurname}`;
        document.getElementById('editUserRole').value = userRole;
        document.getElementById('editUserModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('editUserForm').reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editUserModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }

    // Handle form submission
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userId = document.getElementById('editUserId').value;
        const role = document.getElementById('editUserRole').value;
        
        const data = new URLSearchParams({
            userId: userId,
            role: role
        });

        fetch('/admin/users/role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                window.location.reload();
            } else {
                alert('Error al actualizar el rol: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        });
    });
</script>