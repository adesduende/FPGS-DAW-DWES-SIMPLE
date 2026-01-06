<div class="signup-container">
    <div class="signup-card">
        <div class="signup-header">
            <h2>Crear Cuenta</h2>
            <p class="signup-subtitle">Únete a SportShop y comienza tu aventura deportiva</p>
        </div>

        <?php if ($this->data['error']): ?>
            <div class="alert alert-error">
                <span class="alert-icon">!</span>
                <span><?php echo htmlspecialchars($this->data['error']); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($this->data['success']): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <span><?php echo htmlspecialchars($this->data['success']); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="/auth/register" class="signup-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">
                        Nombre *
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        name="name"
                        placeholder="Ej: Sergio"
                        value="<?php echo htmlspecialchars($this->data['name']); ?>"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="surname">
                        Apellido *
                    </label>
                    <input
                            type="text"
                            id="surname"
                            name="surname"
                            placeholder="Ej: Lopez Lobo"
                            value="<?php echo htmlspecialchars($this->data['surname']); ?>"
                            required
                    >
                </div>

                <div class="form-group span-2">
                    <label for="email">
                        Correo Electrónico *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="tu@email.com"
                        value="<?php echo htmlspecialchars($this->data['email']); ?>"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="phone">

                    Teléfono *
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    placeholder="+34 666 666 666"
                    required
                    value="<?php echo htmlspecialchars($this->data['phone']); ?>"
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">
                        Contraseña *
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Mínimo 8 caracteres"
                        required
                    >
                    <small class="form-hint">Usa letras, números y símbolos</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">

                        Confirmar Contraseña *
                    </label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Repite tu contraseña"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-signup">
                <span>Crear Cuenta</span>
                <span class="btn-arrow">→</span>
            </button>
        </form>

        <div class="signup-footer">
            <p>¿Ya tienes cuenta? <a href="/auth/login" class="link-login">Inicia Sesión</a></p>
        </div>
    </div>
</div>