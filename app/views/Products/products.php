<div class="products-page">
    <!-- Page Header -->
    <div class="products-header">
        <h1>Nuestros Productos</h1>
        <p class="products-subtitle">Encuentra el equipamiento deportivo perfecto para ti</p>
    </div>

    <!-- Filters and Search Section -->
    <div class="filters-section">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Buscar productos..."
                value="<?php echo htmlspecialchars($this->data['searchQuery']); ?>">
            <button class="btn-search">Buscar</button>
        </div>

        <div class="filter-controls">
            <div class="filter-group">
                <label>Categoría:</label>
                <select id="categoryFilter" class="filter-select">
                    <?php foreach ($this->data['categories'] as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo $this->data['selectedCategory'] === $cat ? 'selected' : ''; ?>>
                            <?php echo $cat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Ordenar por:</label>
                <select id="sortFilter" class="filter-select">
                    <option value="default" <?php echo $this->data['sortBy'] === 'default' ? 'selected' : ''; ?>>Por
                        defecto
                    </option>
                    <option value="price_asc" <?php echo $this->data['sortBy'] === 'price_asc' ? 'selected' : ''; ?>>
                        Precio: Menor a
                        Mayor
                    </option>
                    <option value="price_desc" <?php echo $this->data['sortBy'] === 'price_desc' ? 'selected' : ''; ?>>
                        Precio: Mayor a
                        Menor
                    </option>
                    <option value="name" <?php echo $this->data['sortBy'] === 'name' ? 'selected' : ''; ?>>Nombre A-Z
                    </option>
                    <option value="rating" <?php echo $this->data['sortBy'] === 'rating' ? 'selected' : ''; ?>>Mejor
                        Valorados
                    </option>
                </select>
            </div>

            <div class="results-count">
                <span><?php echo $this->data['total']; ?> productos encontrados</span>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-container">
        <?php if (empty($this->data['products'])): ?>
            <div class="no-products">
                <h2>No se encontraron productos</h2>
                <p>Intenta con otros filtros o términos de búsqueda</p>
                <a href="/products" class="btn-primary">Ver todos los productos</a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($this->data['products'] as $product): ?>
                    <div class="product-card-full">
                        <?php if (!empty($product->Badge) || $product->Discount >= 1): ?>
                            <div class="product-badge-full
                                <?php
                                echo $product->Badge === "Best Seller" ? 'badge-popular' : '';
                                echo $product->Badge === "New" ? 'badge-new' : '';
                                echo $product->Badge === "Premium" ? 'badge-premium' : '';
                                echo $product->Discount >= 1 ? 'badge-sale' : '';
                                ?>
                            ">
                                <?php echo $product->Badge !== '' ? $product->Badge : $product->Discount . '%' ?>
                            </div>
                        <?php endif; ?>

                        <div class="product-image-full">
                            <img class="product-image" src="<?php echo htmlspecialchars($product->ImageUrl ?? '/images/product-placeholder.png'); ?>"
                                alt="Product Image">
                        </div>

                        <div class="product-info-full">
                            <span class="product-category"><?php echo $product->Category->Name; ?></span>
                            <h3 class="product-name"><?php echo htmlspecialchars($product->Name); ?></h3>

                            <div class="product-rating">
                                <?php
                                $fullStars = floor($product->Rating);
                                $hasHalfStar = ($product->Rating - $fullStars) >= 0.5;

                                for ($i = 0; $i < $fullStars; $i++) {
                                    echo '<span class="star filled">★</span>';
                                }
                                if ($hasHalfStar) {
                                    echo '<span class="star half">★</span>';
                                }
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                for ($i = 0; $i < $emptyStars; $i++) {
                                    echo '<span class="star empty">★</span>';
                                }
                                ?>
                                <span class="rating-value">(<?php echo $product->Rating; ?>)</span>
                            </div>

                            <div class="product-stock">
                                <?php if ($product->Stock > 20): ?>
                                    <span class="stock-high">En stock</span>
                                <?php elseif ($product->Stock > 5): ?>
                                    <span class="stock-medium">Pocas unidades</span>
                                <?php else: ?>
                                    <span class="stock-low">¡Últimas unidades!</span>
                                <?php endif; ?>
                            </div>

                            <div class="product-footer-full">
                                <div class="product-price-full">
                                    <?php
                                    echo $product->Discount >= 1 ? '<del>' . round($product->Price, 2) . '€</del>' : '';
                                    echo round($product->Price - (($product->Discount * $product->Price) / 100), 2) . '€';
                                    ?>
                                </div>
                                <button class="btn-add-cart" onclick="AddToCart('<?php echo $product->Id->Id ?>')">
                                    <img src="/images/cart-add.svg" alt="Añadir">
                                </button>
                            </div>

                            <button class="btn-quick-view" onclick="openProductModal(
                                '<?= htmlspecialchars($product->Id->Id, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($product->Name, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($product->Category->Name, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($product->Description ?? 'Sin descripción disponible', ENT_QUOTES) ?>',
                                '<?= $product->Price ?>',
                                '<?= $product->Discount ?>',
                                '<?= $product->Rating ?>',
                                '<?= $product->Stock ?>',
                                '<?= htmlspecialchars($product->Badge ?? '', ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($product->ImageUrl ?? '', ENT_QUOTES) ?>'
                            )">Vista Rápida</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- Pagination Section -->
    <?php if (!empty($this->data['products'])): ?>
        <?php
        $query = '&items=' . $this->data['unitsPerPage'];
        $query .= $this->data['searchQuery'] ? '&search=' . urlencode($this->data['searchQuery']) : '';
        $query .= $this->data['selectedCategory'] ? '&category=' . urlencode($this->data['selectedCategory']) : '';
        $query .= $this->data['sortBy'] ? '&sort=' . urlencode($this->data['sortBy']) : '';
        ?>
        <div class="pagination">
            <button class="page-btn page-prev" <?php if ($this->data['currentPage'] == 1): ?>disabled<?php endif; ?>
                onclick="window.location.href='/products?page=<?php echo $this->data['currentPage'] - 1 ?><?php echo $query; ?>'">
                «
            </button>
            <?php for ($i = 1; $i <= $this->data['totalPages']; $i++): ?>
                <a class="page-btn page-num <?php echo ($i === $this->data['currentPage']) ? 'active' : '' ?>"
                    href="/products?page=<?php echo $i ?><?php echo $query; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <button class="page-btn page-next" <?php if ($this->data['totalPages'] == $this->data['currentPage']): ?>disabled<?php endif; ?>
                onclick="window.location.href='/products?page=<?php echo $this->data['currentPage'] + 1 ?><?php echo $query; ?>'">
                »
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Product Details Modal -->
<div id="productModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2 id="modalProductName"></h2>
            <span class="close-modal" onclick="closeProductModal()">✖</span>
        </div>
        <div class="modal-body">
            <div class="modal-product-layout">
                <div class="modal-product-image">
                    <img class="product-image" id="modalProductImage" src="" alt="Product Image">
                    <div id="modalBadge" class="product-badge-modal"></div>
                </div>

                <div class="modal-product-details">
                    <div class="modal-detail-group">
                        <label>Categoría:</label>
                        <span id="modalCategory" class="detail-value"></span>
                    </div>

                    <div class="modal-detail-group">
                        <label>Valoración:</label>
                        <div class="detail-value">
                            <span id="modalRating" class="rating-stars"></span>
                            <span id="modalRatingValue" class="rating-number"></span>
                        </div>
                    </div>

                    <div class="modal-detail-group">
                        <label>Precio:</label>
                        <div class="detail-value">
                            <span id="modalPriceOriginal" class="price-original"></span>
                            <span id="modalPriceFinal" class="price-final"></span>
                            <span id="modalDiscount" class="discount-badge"></span>
                        </div>
                    </div>

                    <div class="modal-detail-group">
                        <label>Stock:</label>
                        <span id="modalStock" class="detail-value stock-info"></span>
                    </div>

                    <div class="modal-detail-group full-width">
                        <label>Descripción:</label>
                        <p id="modalDescription" class="detail-description"></p>
                    </div>

                    <div class="modal-actions">
                        <button class="btn-add-to-cart-modal">Añadir al Carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple filter functionality
    document.getElementById('categoryFilter')?.addEventListener('change', function () {
        const category = this.value;
        const sort = document.getElementById('sortFilter').value;
        const search = document.getElementById('searchInput').value;
        window.location.href = `/products?category=${encodeURIComponent(category)}&sort=${sort}&search=${encodeURIComponent(search)}`;
    });

    document.getElementById('sortFilter')?.addEventListener('change', function () {
        const category = document.getElementById('categoryFilter').value;
        const sort = this.value;
        const search = document.getElementById('searchInput').value;
        window.location.href = `/products?category=${encodeURIComponent(category)}&sort=${sort}&search=${encodeURIComponent(search)}`;
    });

    document.querySelector('.btn-search')?.addEventListener('click', function () {
        const category = document.getElementById('categoryFilter').value;
        const sort = document.getElementById('sortFilter').value;
        const search = document.getElementById('searchInput').value;
        window.location.href = `/products?category=${category}&sort=${sort}&search=${encodeURIComponent(search)}`;
    });

    document.getElementById('searchInput')?.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            document.querySelector('.btn-search').click();
        }
    });

    // Product Modal Functions
    function openProductModal(id, name, category, description, price, discount, rating, stock, badge, imageUrl) {
        // Set product name
        document.getElementById('modalProductName').textContent = name;

        // Set category
        document.getElementById('modalCategory').textContent = category;

        // Set description
        document.getElementById('modalDescription').textContent = description;

        // Set rating
        const fullStars = Math.floor(rating);
        const hasHalfStar = (rating - fullStars) >= 0.5;
        let starsHTML = '';

        for (let i = 0; i < fullStars; i++) {
            starsHTML += '<span class="star filled">★</span>';
        }
        if (hasHalfStar) {
            starsHTML += '<span class="star half">★</span>';
        }
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        for (let i = 0; i < emptyStars; i++) {
            starsHTML += '<span class="star empty">★</span>';
        }

        document.getElementById('modalRating').innerHTML = starsHTML;
        document.getElementById('modalRatingValue').textContent = `(${rating})`;

        // Set price
        const discountNum = parseFloat(discount);
        const priceNum = parseFloat(price);
        const finalPrice = priceNum - ((discountNum * priceNum) / 100);

        if (discountNum > 0) {
            document.getElementById('modalPriceOriginal').innerHTML = `<del>${priceNum.toFixed(2)}€</del>`;
            document.getElementById('modalPriceFinal').textContent = `${finalPrice.toFixed(2)}€`;
            document.getElementById('modalDiscount').textContent = `-${discountNum}%`;
            document.getElementById('modalDiscount').style.display = 'inline-block';
        } else {
            document.getElementById('modalPriceOriginal').textContent = '';
            document.getElementById('modalPriceFinal').textContent = `${priceNum.toFixed(2)}€`;
            document.getElementById('modalDiscount').style.display = 'none';
        }

        // Set stock
        const stockNum = parseInt(stock);
        let stockHTML = '';
        if (stockNum > 20) {
            stockHTML = '<span class="stock-high">En stock (' + stockNum + ' unidades)</span>';
        } else if (stockNum > 5) {
            stockHTML = '<span class="stock-medium">Pocas unidades (' + stockNum + ' disponibles)</span>';
        } else if (stockNum > 0) {
            stockHTML = '<span class="stock-low">¡Últimas ' + stockNum + ' unidades!</span>';
        } else {
            stockHTML = '<span class="stock-out">Sin stock</span>';
        }
        document.getElementById('modalStock').innerHTML = stockHTML;

        // Set badge
        const badgeElement = document.getElementById('modalBadge');
        if (badge || discountNum > 0) {
            badgeElement.textContent = badge || `${discountNum}%`;
            badgeElement.className = 'product-badge-modal ';
            if (badge === 'Best Seller') badgeElement.className += 'badge-popular';
            else if (badge === 'New') badgeElement.className += 'badge-new';
            else if (badge === 'Premium') badgeElement.className += 'badge-premium';
            else if (discountNum > 0) badgeElement.className += 'badge-sale';
            badgeElement.style.display = 'block';
        } else {
            badgeElement.style.display = 'none';
        }

        //Set image
        document.querySelector('.modal-product-image #modalProductImage').setAttribute('src', imageUrl);

        //Set Add to Cart button action
        document.querySelector('.btn-add-to-cart-modal').onclick = function () {
            AddToCart(id);
        };

        // Show modal
        document.getElementById('productModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        document.getElementById('productModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('productModal');
        if (event.target === modal) {
            closeProductModal();
        }
    }

    //Add to cart function
    function AddToCart(productId) {
        const data = new URLSearchParams({ product_id: productId })
        fetch('/user/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data,
        })
            .then(response => response.json())
            .then(data => {
                fetch('/user/cart/count')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('cart-count').innerText = data;
                    })
            })
    }
</script>