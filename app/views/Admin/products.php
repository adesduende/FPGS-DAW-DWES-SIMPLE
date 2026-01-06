<div class="admin-container">
    <h1 style="text-align: center; color: #333; margin-bottom: 2rem; font-size: 2.5rem;">Gestión de Productos</h1>

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
                <h3>Total Productos</h3>
                <p class="stat-number"><?php echo $this->data['totalProducts'] ?? '0'; ?></p>
            </div>
            <div class="stat-card">
                <h3>En Stock</h3>
                <p class="stat-number"><?php echo $this->data['inStock'] ?? '0'; ?></p>
            </div>
            <div class="stat-card">
                <h3>Sin Stock</h3>
                <p class="stat-number"><?php echo $this->data['outOfStock'] ?? '0'; ?></p>
            </div>
            <div class="stat-card">
                <h3>Categorías</h3>
                <p class="stat-number"><?php echo $this->data['categoriesCount'] ?? '0'; ?></p>
            </div>
            <div class="stat-card">
                <h3>Desactivados</h3>
                <p class="stat-number"><?php echo $this->data['deactivated'] ?? '0'; ?></p>
            </div>
        </div>

        <div class="table-section">
            <div class="table-header">
                <h2>Lista de Productos</h2>
                <button class="btn-add" onclick="openAddModal()">Añadir Producto</button>
            </div>           

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Discount</th>
                            <th>Estado</th>
                            <th>Activado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->data['products'] as $product): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($product->ImageUrl ?? '') ?>" alt="🥍" class="product-image"></td>
                                <td><?php echo htmlspecialchars($product->Name); ?></td>
                                <td><?php echo htmlspecialchars($product->Category->Name); ?></td>
                                <td><?php echo htmlspecialchars(number_format($product->Price, 2)); ?> €</td>
                                <td><?php echo htmlspecialchars($product->Stock); ?></td>
                                <td><?php echo htmlspecialchars($product->Discount); ?> %</td>
                                <td>
                                    <?php if ($product->Stock > 10): ?>
                                        <span class="status in-stock">🟢</span>
                                    <?php elseif ($product->Stock > 0): ?>
                                        <span class="status low-stock">🟠</span>
                                    <?php else: ?>
                                        <span class="status out-of-stock">🔴</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($product->IsActive): ?>
                                        <span class="status active">✅</span>
                                    <?php else: ?>
                                        <span class="status inactive">❌</span>
                                    <?php endif; ?>
                                <td>
                                    <div class="actions">
                                        <button class="action-btn action-btn-edit" 
                                            onclick="openEditModal('<?= $product->Id->Id ?>', '<?= htmlspecialchars($product->Name) ?>', '<?= htmlspecialchars($product->Description ?? '') ?>', '<?= $product->Category->Id->Id ?>', '<?= $product->Price ?>', '<?= $product->Stock ?>', '<?= $product->Discount ?>', '<?= htmlspecialchars($product->ImageUrl ?? '') ?>')">✏️</button>
                                        <?php if ($product->IsActive): ?>
                                            <button class="action-btn action-btn-deactivate" 
                                                onclick="DeactivateProduct('<?= $product->Id->Id ?>')">🚫</button>
                                        <?php else: ?>
                                            <button class="action-btn action-btn-activate" 
                                                onclick="ActivateProduct('<?= $product->Id->Id ?>')">♻️</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="page-btn" <?php echo $this->data['currentPage'] <= 1 ? 'disabled' : ''; ?>
                    onclick="NavigateToPage(<?php echo $this->data['currentPage'] - 1 > 0 ? $this->data['currentPage'] - 1 : 1; ?>)">«
                </button>
                <?php for ($i = 1; $i <= $this->data['totalPages']; $i++): ?>
                    <button class="page-btn <?php echo $i === $this->data['currentPage'] ? 'active' : '' ?>"
                        onclick="NavigateToPage(<?php echo $i; ?>)">
                        <?= $i ?>
                    </button>
                <?php endfor; ?>
                <button class="page-btn" <?php echo $this->data['currentPage'] >= $this->data['totalPages'] ? 'disabled' : ''; ?>
                    onclick="NavigateToPage(<?php echo $this->data['currentPage'] + 1 < $this->data['totalPages'] ? $this->data['currentPage'] + 1 : $this->data['totalPages']; ?>)">»
                </button>
            </div>
        </div>
    </main>
</div>

<div id="addProductModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Añadir Nuevo Producto</h2>
            <span class="close-modal" onclick="closeAddModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addProductForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="addProductName" class="form-label">Nombre del Producto:</label>
                        <input type="text" id="addProductName" name="name" class="form-input" placeholder="Ej: Smartphone Galaxy X" required>
                    </div>

                    <div class="form-group">
                        <label for="addProductCategory" class="form-label">Categoría:</label>
                        <select id="addProductCategory" name="categoryId" class="form-select" required>
                            <option value="">Selecciona una categoría</option>
                            <?php foreach ($this->data['categories'] as $category): ?>
                                <option value="<?= $category->Id->Id ?>"><?= htmlspecialchars($category->Name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="addProductDescription" class="form-label">Descripción:</label>
                    <textarea id="addProductDescription" name="description" class="form-textarea" rows="3" placeholder="Descripción del producto..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="addProductPrice" class="form-label">Precio (€):</label>
                        <input type="number" id="addProductPrice" name="price" class="form-input" placeholder="0.00" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="addProductStock" class="form-label">Stock:</label>
                        <input type="number" id="addProductStock" name="stock" class="form-input" placeholder="0" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="addProductDiscount" class="form-label">Descuento (%):</label>
                        <input type="number" id="addProductDiscount" name="discount" class="form-input" placeholder="0" min="0" max="100" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="addProductImage" class="form-label">URL de la Imagen:</label>
                    <input type="url" id="addProductImage" name="image" class="form-input" placeholder="https://ejemplo.com/imagen.jpg">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">Crear Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editProductModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Editar Producto</h2>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editProductForm">
                <input type="hidden" id="editProductId" name="id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editProductName" class="form-label">Nombre del Producto:</label>
                        <input type="text" id="editProductName" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="editProductCategory" class="form-label">Categoría:</label>
                        <select id="editProductCategory" name="categoryId" class="form-select" required>
                            <?php foreach ($this->data['categories'] as $category): ?>
                                <option value="<?= $category->Id->Id ?>"><?= htmlspecialchars($category->Name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="editProductDescription" class="form-label">Descripción:</label>
                    <textarea id="editProductDescription" name="description" class="form-textarea" rows="3"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editProductPrice" class="form-label">Precio (€):</label>
                        <input type="number" id="editProductPrice" name="price" class="form-input" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="editProductStock" class="form-label">Stock:</label>
                        <input type="number" id="editProductStock" name="stock" class="form-input" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="editProductDiscount" class="form-label">Descuento (%):</label>
                        <input type="number" id="editProductDiscount" name="discount" class="form-input" min="0" max="100">
                    </div>
                </div>

                <div class="form-group">
                    <label for="editProductImage" class="form-label">URL de la Imagen:</label>
                    <input type="url" id="editProductImage" name="image" class="form-input">
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

    function DeactivateProduct(productId) {        
        fetch('/admin/products/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id: productId, activate: '0' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } 
        })
    }
    function ActivateProduct(productId) {
        fetch('/admin/products/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id: productId, activate: '1' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } 
        })
    }

    // Add Product Modal Functions
    function openAddModal() {
        document.getElementById('addProductModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeAddModal() {
        document.getElementById('addProductModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('addProductForm').reset();
    }

    // Edit Product Modal Functions
    function openEditModal(id, name, description, categoryId, price, stock, discount, image) {
        document.getElementById('editProductId').value = id;
        document.getElementById('editProductName').value = name;
        document.getElementById('editProductDescription').value = description;
        document.getElementById('editProductCategory').value = categoryId;
        document.getElementById('editProductPrice').value = price;
        document.getElementById('editProductStock').value = stock;
        document.getElementById('editProductDiscount').value = discount;
        document.getElementById('editProductImage').value = image;
        document.getElementById('editProductModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editProductModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('editProductForm').reset();
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addProductModal');
        const editModal = document.getElementById('editProductModal');
        
        if (event.target === addModal) {
            closeAddModal();
        } else if (event.target === editModal) {
            closeEditModal();
        }
    }

    // Add Product Form Submission
    document.getElementById('addProductForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new URLSearchParams({
            name: document.getElementById('addProductName').value,
            description: document.getElementById('addProductDescription').value,
            categoryId: document.getElementById('addProductCategory').value,
            price: document.getElementById('addProductPrice').value,
            stock: document.getElementById('addProductStock').value,
            discount: document.getElementById('addProductDiscount').value,
            image: document.getElementById('addProductImage').value
        });

        fetch('/admin/products/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeAddModal();
                location.reload();
            }
        });
    });

    // Edit Product Form Submission
    document.getElementById('editProductForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new URLSearchParams({
            id: document.getElementById('editProductId').value,
            name: document.getElementById('editProductName').value,
            description: document.getElementById('editProductDescription').value,
            categoryId: document.getElementById('editProductCategory').value,
            price: document.getElementById('editProductPrice').value,
            stock: document.getElementById('editProductStock').value,
            discount: document.getElementById('editProductDiscount').value,
            image: document.getElementById('editProductImage').value
        });

        fetch('/admin/products/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                location.reload();
            }
        });
    });
</script>
