<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlásenie</title>

    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/signin.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php require "./assets/header.php" ?>

    <main>

        <section class="signin-form">
            
            <h1>Prihlásenie</h1>
            <form action="./admin/log-in.php" method="POST">
                <input type="email" name="user_email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Heslo" required><br>
                <input type="submit" value="Prihlásiť">
            </form>

        </section>

    </main>

    <?php require "./assets/footer.php" ?>
    <script src="./js/header.js"></script>
</body>
</html>