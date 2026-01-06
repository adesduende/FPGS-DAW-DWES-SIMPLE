
<main>
    <section class="hero-section">
        <div class="hero-content">
            <h1>Bienvenido a SportShop</h1>
            <p class="hero-subtitle">Tu tienda de confianza para equipamiento deportivo de calidad</p>
            <a href="/products" class="hero-btn">Ver Todos los Productos</a>
        </div>
    </section>

    <section class="categories-section">
        <h2 class="section-title">Categorías Populares</h2>
        <div class="categories-grid">
            <?php foreach($this->data['categories'] as $category): ?>
            <a class="category-card" href="/products?category=<?php echo urlencode($category->Name); ?>">
                <div class="category-icon"></div>
                <h3><?php echo htmlspecialchars($category->Name); ?></h3>
                <p><?php echo htmlspecialchars($category->Description); ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="featured-section">
        <h2 class="section-title">Productos Destacados</h2>
        <div class="products-grid">
            <?php foreach ($this->data['products'] as $product): ?>
                <div class="product-card">
                    <?php if (!empty($product->Badge) || $product->Discount >= 1): ?>
                    <?php
                    // Prioridad: descuento primero
                    if ($product->Discount >= 1) {
                        $badgeClass = 'badge-sale';
                        $badgeText = $product->Discount . '%';
                    } else {
                        // Si no hay descuento, usar el badge
                        switch($product->Badge) {
                            case 'Best Seller':
                            case 'Popular':
                                $badgeClass = 'badge-popular';
                                $badgeText = $product->Badge;
                                break;
                            case 'New':
                                $badgeClass = 'badge-new';
                                $badgeText = $product->Badge;
                                break;
                            case 'Premium':
                                $badgeClass = 'badge-premium';
                                $badgeText = $product->Badge;
                                break;
                            default:
                                $badgeClass = '';
                                $badgeText = $product->Badge;
                                break;
                        }
                    }
                    ?>
                    <div class="product-badge <?php echo htmlspecialchars($badgeClass); ?>">
                        <?php echo htmlspecialchars($badgeText); ?>
                    </div>
                    <?php endif; ?>
                    <img class="product-image" src="<?php echo htmlspecialchars($product->ImageUrl) ?>" alt="<?php echo htmlspecialchars($product->Name) ?>">
                    
                    <h3 class="product-title"><?php echo htmlspecialchars($product->Name) ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($product->Description) ?></p>
                    <div class="product-footer">
                        <span class="product-price">
                            <?php
                            echo $product->Discount>=1?'<del>'.htmlspecialchars(round($product->Price,2)).'€</del>':'';
                            echo htmlspecialchars(round($product->Price-(($product->Discount*$product->Price)/100),2)).'€';
                            ?>
                        </span>
                        <button class="add-to-cart-btn" onclick="AddToCart('<?php echo htmlspecialchars($product->Id->Id) ?>')">
                            <img src="/images/cart-add.svg" alt="Añadir">
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<script>
    function AddToCart(productId)
    {
        const data = new URLSearchParams({product_id: productId})
        fetch('/user/cart/add',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data,
        })
            .then(response=>response.json())
            .then(data=>{
                fetch('/user/cart/count')
                    .then(response=>response.json())
                    .then(data=>{
                        document.getElementById('cart-count').innerText = data;
                    })
            })
    }
</script>