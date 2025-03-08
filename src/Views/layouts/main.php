<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'App km' ?></title>
    <link rel="stylesheet" href=" <?= BASE_URL . '/src/Views/css/main.css' ?>">   

</head>
<body>
<header>
    <h1>APP KM</h1>
    <button class="menu-toggle" aria-label="Abrir menú">&#9776;</button>
    <nav>
        <ul class="menu">
            <li><a href="<?= BASE_URL . 'home' ?>">Inicio</a></li>
            <li><a href="<?= BASE_URL . 'valesFast' ?>">Llenar vales</a></li>
            <li>
                <a href="#">Mantenimiento</a>
                <ul>
                    <li><a href="<?= BASE_URL . 'vehiculos' ?>">Vehiculos</a></li>
                    <li><a href="<?= BASE_URL . 'conductores' ?>">Motoristas</a></li>
                    <li><a href="<?= BASE_URL . 'auxiliares' ?>">Auxiliares</a></li>
                    <li><a href="<?= BASE_URL . 'responsables' ?>">Responsables</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Tareas varias</a>
                <ul>
                    <li><a href="<?= BASE_URL . 'uso-diario' ?>">Uso diario      </a></li>
                    <li><a href="<?= BASE_URL . 'vales' ?>">Vales      </a></li>
                    <li><a href="<?= BASE_URL . 'valesDetalle' ?>">Detalles de vale</a></li>
                    <li><a href="<?= BASE_URL . 'valesDetalle2' ?>">Mover detalles</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Reporteria</a>
                <ul>
                    <li><a href="<?= BASE_URL . 'reporte-uso-diario' ?>">Uso diario</a></li>                    
                </ul>
            </li>
            <li>
                <a href="#">Configuraciones</a>
                <ul>
                    <li><a href="<?= BASE_URL . 'usuario' ?>">Usuarios</a></li>
                    <li><a href="<?= BASE_URL . 'perfil' ?>">Perfiles</a></li>
                    <li><a href="<?= BASE_URL . 'permiso' ?>">Permisos</a></li>
                </ul>
            </li>
            <li><a href="<?= BASE_URL . 'logout' ?>">Cerrar sesión</a></li>
        </ul>
    </nav>
</header>


    <main>
        <?= $content ?? '' ?>

        <?= JS ?>
    </main>
    <footer>
        <?php 
        use App\Controllers\usuarioController;
        $nombre = new usuarioController() ;
        $nombre = $nombre -> getUsuario ($_SESSION['user_id']);
        $nombreU = $nombre['nombre_apellido'] ?? 'Usuario no identificado';
        ?>
         <p><?= htmlspecialchars("Usuario: ".$nombreU) ?></p>
        <p>&copy; 2025 App km</p>
        <p>Cardaya Dev</p>
    </footer>
</body>

</html>


