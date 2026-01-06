<div class="cart-container">
    <div class="cart-header">
        <h1>Tu Carrito de Compras</h1>
        <p class="cart-items-count"><?php echo count($this->data['cart_items']); ?> productos</p>
    </div>

    <div class="cart-content">
        <div class="cart-items-section">
            <?php if (empty($this->data['cart_items'])): ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon"></div>
                    <h2>Tu carrito está vacío</h2>
                    <a href="/" class="btn-primary">Ir a la Tienda</a>
                </div>
            <?php else: ?>
                <?php foreach ($this->data['cart_items'] as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img class="product-image" src="<?php echo htmlspecialchars($item->Product->ImageUrl); ?>" alt="Product Image">
                        </div>
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item->Product->Name); ?></h3>
                            <p class="item-description"><?php echo htmlspecialchars($item->Product->Description); ?></p>
                            <button class="btn-remove" onclick="removeItem('<?php echo htmlspecialchars($item->Product->Id->Id); ?>')">Eliminar</button>
                        </div>
                        <div class="item-quantity">
                            <label>Cantidad:</label>
                            <div class="quantity-control">
                                <button class="qty-btn qty-minus"
                                        onclick="decrease(this,'<?php echo $item->Product->Id->Id ?>')">−
                                </button>
                                <input name="quantity" type="number" value="<?php echo $item->Quantity; ?>" readonly
                                       min="1" max="99" class="qty-input">
                                <button class="qty-btn qty-plus"
                                        onclick="add(this,'<?php echo $item->Product->Id->Id ?>')">+
                                </button>
                            </div>
                        </div>
                        <div class="item-price">
                            <div class="price-per-unit">€<?php echo number_format($item->Product->Price, 2); ?> /
                                unidad
                            </div>
                            <div class="price-total">
                                €<?php echo number_format($item->Product->Price * $item->Quantity, 2); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Continue Shopping Button -->
                <div class="continue-shopping">
                    <a href="/" class="btn-secondary">Continuar Comprando</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cart Summary Section -->
        <?php if (!empty($this->data['cart_items'])): ?>
            <div class="cart-summary">
                <h2>Resumen del Pedido</h2>

                <div class="summary-line">
                    <span>Subtotal</span>
                    <span><?php echo number_format($this->data['cart_items_subtotal'], 2); ?>€</span>
                </div>

                <div class="summary-line">
                    <span>Envío</span>
                    <span><?php echo number_format($this->data['cart_items_shipping'], 2); ?>€</span>
                </div>

                <div class="summary-line">
                    <span>IVA (21%)</span>
                    <span><?php echo number_format($this->data['cart_items_tax'], 2); ?>€</span>
                </div>

                <hr class="summary-divider">

                <div class="summary-line summary-total">
                    <span>Total</span>
                    <span><?php echo number_format($this->data['cart_items_total'], 2); ?>€</span>
                </div>

                <button class="btn-checkout" onclick="window.location.href='/user/cart/buy'">
                    Proceder al Pago
                </button>

                <!-- Security Badge -->
                <div class="security-badge">
                    <div class="badge-icon">🔒</div>
                    <div class="badge-text">
                        <strong>Compra Segura</strong>
                        <p>Tus datos están protegidos</p>
                    </div>
                </div>

                <!-- Accepted Payments -->
                <div class="payment-methods">
                    <h4>Métodos de pago aceptados</h4>
                    <div class="payment-icons">
                        <span class="payment-icon">💳</span>
                        <span class="payment-icon">🏦</span>
                        <span class="payment-icon">📱</span>
                        <span class="payment-icon">💰</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    function removeItem(id)
    {
        const data = new URLSearchParams({pid: `${id}`})
        fetch(`/user/cart/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data,
        })
            .then(
                response=>response.json()
            )
            .then(
                data=>{
                    if(data.updated===1)
                        window.location.reload();

                }
            );
    }
    function add(target, id) {
        const $counter = target.previousElementSibling;
        const value = $counter.value;
        const data = new URLSearchParams({pid: `${id}`, qty: `${parseInt(value) + 1}`})
        fetch(`/user/cart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data,
        })
            .then(
                response=>response.json()
            )
            .then(
                data=>{
                    if(data.updated===1)
                        window.location.reload();

                }
            );

    }

    function decrease(target, id) {
        const $counter = target.nextElementSibling;
        const value = $counter.value;
        const data = new URLSearchParams({pid: `${id}`, qty: `${parseInt(value) - 1}`})
        fetch(`/user/cart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data,
        })
            .then(
                response=>response.json()
            )
            .then(
                data=>{
                    if(data.updated===1)
                        window.location.reload();
                }
            );
    }
</script>