<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'App km' ?></title>
    <!-- Uso de una ruta relativa con barra diagonal -->
    <link rel="stylesheet" href=" <?= BASE_URL.'/src/Views/css/login.css' ?>">

</head>
<body>
    <header>
        
    </header>
    <main>
        <?= $content ?? '' ?>
    </main>
    <footer>
    </footer>
</body>
</html>


