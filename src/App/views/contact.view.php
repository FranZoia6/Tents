<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./../style/reset.css" rel="stylesheet" type="text/css">
    <link href="./../style/styles.css" rel="stylesheet" type="text/css">

    <title>Contacto</title>
</head>
<body>
    <header>
    </header>

    <?php
        require 'parts/nav.view.php'
    ?>
    
    <main>
        <h1>Contacto</h1>
        <form action="/contact" method = "POST">
            <label for="subjet"><strong>Asunto(*)</strong></label>
            <input type="text" name = "subject">
            <label for="email"><strong>Correo</strong></label>
            <input type="text"name = "mail">
            <label for="description"><strong>Descripcion</strong></label>
            <textarea name="description"cols="30" rows="10"></textarea>
            <input type="submit" name="submit" value="Enviar">
        </form>

    </main>

    <?php
        require 'parts/footer.view.php'
    ?>

</body>
</html>