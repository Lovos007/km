<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'App km' ?></title>
    <link rel="stylesheet" href=" <?= BASE_URL . '/src/Views/css/main.css' ?>">   

</head>
<body>
    <main>
        <?= $content ?? '' ?>

        <?= JS ?>
    </main>
   
</body>

</html>


