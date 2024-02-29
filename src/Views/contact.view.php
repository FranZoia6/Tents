<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/public/style/reset.css" rel="stylesheet" type="text/css">
    <link href="/public/style/styles.css" rel="stylesheet" type="text/css">

    <title>Contacto</title>
</head>
<body>
    <header>
        <figure>
            <img src="/public/imagenes/carpasHeader.png" alt="Carpas">
        </figure>
    </header>

    <nav>
        <ul>
            <?php foreach ($this->menu as $item) : ?>
            <li><a href="<?= $item["href"] ?>"><?=$item["name"]?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    
    <main>
        <h1>Contacto</h1>
    
    </main>
    <footer>
        <section>
        </section>
    </footer>
</body>
</html>