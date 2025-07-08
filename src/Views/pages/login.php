

<div class="login-container">
    <h1>Bienvenido a KM</h1>
    <h2>Inicia sesión</h2>

    <!-- Formulario de login -->
    <form action="<?= BASE_URL . 'src/Views/ajax/loginRespuesta.php' ?>" method="POST">
        <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" required placeholder="Escribe tu nombre de usuario">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required placeholder="Escribe tu contraseña">
        </div>

        <?php
        if (isset($error) && !empty($error)) {
            echo '<div class="error-message">' . $error . '</div>';
        }
        ?>

        <button type="submit">Iniciar sesión</button>
    </form>
</div>
