
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
                <h3><?php echo $category->Name; ?></h3>
                <p><?php echo $category->Description; ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="featured-section">
        <h2 class="section-title">Productos Destacados</h2>
        <div class="products-grid">
            <?php foreach ($this->data['products'] as $product): ?>
                <div class="product-card">
                    <div class="product-badge
                        <?php
                         echo $product->Badge==="Best Seller"?'badge-popular':'';
                         echo $product->Badge==="New"?'badge-new':'';
                         echo $product->Badge==="Premium"?'badge-premium':'';
                         echo $product->Discount>=1?'badge-sale':'';
                         ?>
                    "><?php echo $product->Badge!==''?$product->Badge:$product->Discount.'%' ?></div>
                    
                        <img class="product-image" src="<?php echo $product->ImageUrl ?>" alt="<?php echo htmlspecialchars($product->Name) ?>">
                    
                    <h3 class="product-title"><?php echo $product->Name ?></h3>
                    <p class="product-description"><?php echo $product->Description ?></p>
                    <div class="product-footer">
                        <span class="product-price">
                            <?php
                            echo $product->Discount>=1?'<del>'.round($product->Price,2).'€</del>':'';
                            echo round($product->Price-(($product->Discount*$product->Price)/100),2).'€';
                            ?>
                        </span>
                        <button class="add-to-cart-btn" onclick="AddToCart('<?php echo $product->Id->Id ?>')">
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