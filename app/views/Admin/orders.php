<div class="admin-container">
    <h1 style="text-align: center; color: #333; margin-bottom: 2rem; font-size: 2.5rem;">Gestión de Pedidos</h1>

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
                <h3>Total Pedidos</h3>
                <p class="stat-number"><?php echo htmlspecialchars($this->data['ordersCount'] ?? '0'); ?></p>
            </div>
            <div class="stat-card">
                <h3>Pendientes</h3>
                <p class="stat-number"><?php echo htmlspecialchars($this->data['ordersPendant'] ?? '0'); ?></p>
            </div>
            <div class="stat-card">
                <h3>En Proceso</h3>
                <p class="stat-number"><?php echo htmlspecialchars($this->data['ordersProcessing'] ?? '0'); ?></p>
            </div>
            <div class="stat-card">
                <h3>Enviados</h3>
                <p class="stat-number"><?php echo htmlspecialchars($this->data['ordersShipped'] ?? '0'); ?></p>
            </div>
            <div class="stat-card">
                <h3>Entregados</h3>
                <p class="stat-number"><?php echo htmlspecialchars($this->data['ordersDelivered'] ?? '0'); ?></p>
            </div>
            <div class="stat-card">
                <h3>Cancelados</h3>
                <p class="stat-number"><?php echo htmlspecialchars($this->data['ordersCancelled'] ?? '0'); ?></p>
            </div>
        </div>

        <div class="table-section">
            <div class="table-header">
                <h2>Lista de Pedidos</h2>
            </div>

            <div class="table-filters">
                <input type="text" placeholder="Buscar por ID o cliente..." class="search-input">
                <select class="filter-select">
                    <option value="all">Todos los estados</option>
                    <option value="pending">Pendientes</option>
                    <option value="processing">En proceso</option>
                    <option value="shipped">Enviados</option>
                    <option value="delivered">Entregados</option>
                    <option value="cancelled">Cancelados</option>
                </select>
                <button class="filter-btn" onclick="LookFor()">Filtrar</button>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Email</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Productos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->data['orders'] as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order->OrderNumber); ?></td>
                            <td><?php echo htmlspecialchars($this->data['userperorder'][$order->Id->Id]->Email ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($order->CreatedAt->format('d/m/Y H:i')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order->Total, 2)); ?> €</td>
                            <td><span class="order-status status-<?php echo strtolower($order->Status); ?>"><?php echo htmlspecialchars(ucfirst($order->Status)); ?></span></td>
                            <td><?php echo htmlspecialchars($this->data['productsperorder'][$order->Id->Id] ?? 0); ?></td> 
                            <td class="actions">
                                <button class="action-btn-view">📋</button>
                                <button class="action-btn-edit" onclick="openStatusModal('<?= htmlspecialchars($order->Id->Id) ?>', '<?= htmlspecialchars($order->OrderNumber) ?>', '<?= htmlspecialchars(strtolower($order->Status)) ?>')">✏️</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <button class="page-btn" <?php echo $this->data['currentPage'] <= 1 ? 'disabled' : ''; ?>
                        onclick="NavigateToPage(
                        <?php echo $this->data['currentPage'] - 1 > 0 ? $this->data['currentPage'] - 1 : 1; ?>)">«</button>
                <?php for ($i = 1; $i <= $this->data['totalPages']; $i++): ?>
                    <button class="page-btn <?php echo $i === $this->data['currentPage'] ? 'active' : '' ?>"
                        onclick="NavigateToPage(<?php echo htmlspecialchars($i); ?>)">
                        <?= htmlspecialchars($i) ?>
                    </button>
                <?php endfor; ?>
                <button class="page-btn" 
                    <?php echo $this->data['currentPage'] >= $this->data['totalPages'] ? 'disabled' : ''; ?>
                    onclick="NavigateToPage(
                        <?php echo $this->data['currentPage'] + 1 < $this->data['totalPages'] ? $this->data['currentPage'] + 1 : $this->data['totalPages']; ?>
                    )" >»
                </button>
            </div>
        </div>
    </main>

</div>

<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Actualizar Estado del Pedido</h2>
            <span class="close-modal" onclick="closeStatusModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="statusForm">
                <input type="hidden" id="orderId" name="orderId">
                
                <div class="form-group">
                    <label for="orderNumber" class="form-label">Número de Pedido:</label>
                    <input type="text" id="orderNumber" class="form-input" readonly style="background-color: #f8f9fa;">
                </div>

                <div class="form-group">
                    <label for="orderStatus" class="form-label">Estado del Pedido:</label>
                    <select id="orderStatus" name="status" class="form-select" required>
                        <option value="pending">Pendiente</option>
                        <option value="processing">En Proceso</option>
                        <option value="shipped">Enviado</option>
                        <option value="delivered">Entregado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeStatusModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">Actualizar Estado</button>
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

    
    function openStatusModal(orderId, orderNumber, currentStatus) {
        document.getElementById('orderId').value = orderId;
        document.getElementById('orderNumber').value = orderNumber;
        document.getElementById('orderStatus').value = currentStatus;
        document.getElementById('statusModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeStatusModal() {
        document.getElementById('statusModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('statusForm').reset();
    }

    
    window.onclick = function(event) {
        const modal = document.getElementById('statusModal');
        if (event.target === modal) {
            closeStatusModal();
        }
    }

    
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new URLSearchParams({
            orderId: document.getElementById('orderId').value,
            status: document.getElementById('orderStatus').value
        });

        fetch('/admin/orders/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeStatusModal();
                location.reload();
            } 
        });
    });

    
    function LookFor()
    {
        $searchValue = document.querySelector('.search-input').value.toLowerCase();
        $filterState = document.querySelectorAll('.filter-select')[0].value;

        $data = new URLSearchParams(
            {
                search: $searchValue,
                status: $filterState
            }
        );

        window.location.search = $data.toString();
    }

    </script>