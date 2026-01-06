<div class="login-container">
    <h2>Login</h2>
    <?php if ($this->data['error']): ?>
        <div class="error"><?php echo htmlspecialchars($this->data['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="/auth/login">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>