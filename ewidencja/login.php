<?php
session_start();

# If the admin is logged in
if (!isset($_SESSION['user_id']) &&
    !isset($_SESSION['user_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logowanie</title>

        <!-- bootstrap 5-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

        <!-- bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    </head>
<body>
    <div class="d-flex justify-content-center align-items-center"
         style="min-height: 100vh;">
        <form class="p-5 rounded shadow"
              style="max-width: 30rem; width: 100%"
              method="POST"
              action="php/auth.php">

            <h1 class="text-center display-3 pb-4">Logowanie</h1>
            <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?=htmlspecialchars($_GET['error']); ?>
            </div>
            <?php } ?>

            <div class="mb-3">
                <label for="exampleInputEmail1" 
                       class="form-label">Adres email</label>
                <input type="email" 
                       class="form-control"
                       name="email" 
                       id="exampleInputEmail1" 
                       aria-describedby="emailHelp">
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" 
                       class="form-label">Hasło</label>
                <input type="password" 
                       class="form-control"
                       name="password" 
                       id="exampleInputPassword1">
            </div>
            <button type="submit" 
                    class="btn btn-primary">
                    Zaloguj</button>
            <a href="index.php">Strona główna</a>
        </form>
    </div>
</body>

</html>

<?php }else{
    header("Location: admin.php");
    exit;
} ?>
