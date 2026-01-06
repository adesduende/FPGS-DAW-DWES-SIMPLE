<header>
    <div class="header-top">
        <div class="user-panel">
            <?php if ($this->data['isLogin']): ?>
                <span class="welcome-msg">Hola, <strong><?php echo htmlspecialchars($this->data['name'] ?? 'Usuario'); ?></strong></span>
                <a href="/user/profile" class="user-icon" title="Mi Perfil">Perfil</a>
                <?php if ($this->data['isAdmin']): ?>
                    <a href="/admin/users" class="admin-btn" title="Panel de Administrador">Admin</a>
                <?php endif; ?>
                <a href="/auth/logout" class="logout-btn">Cerrar Sesión</a>
            <?php else: ?>
                <a href="/auth/login" class="login-btn">Iniciar Sesión</a>
                <a href="/auth/register" class="register-btn">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
        if($_SERVER['PATH_INFO']!=='/admin/panel'):
    ?>
            <nav class="main-nav">
                <div class="logo">
                    <a href="/">SportShop</a>
                </div>
                <ul class="nav-menu">
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/products">Productos</a></li>
                </ul>
                <div class="nav-icons">
                    <a href="/user/cart" class="cart-icon" title="Ver carrito">
                        <img src="/images/cart.svg" alt="Carrito">
                        <span class="cart-count" id="cart-count">0</span>
                    </a>
                </div>
            </nav>
    <?php endif; ?>
</header>
<script>
    fetch('/user/cart/count')
        .then(response=>response.json())
        .then(data=>{
            document.getElementById('cart-count').innerText = data;
        })
</script>
