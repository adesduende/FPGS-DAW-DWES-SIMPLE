<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/main.css" />
    <title>Home - Mi Sitio Web</title>
</head>

<body>

    <?php include BASE_PATH . "/app/views/shared/header.php" ?>
    <?php
        include BASE_PATH . $view;
    ?>
    <?php include BASE_PATH . "/app/views/shared/footer.php" ?>

</body>

</html>