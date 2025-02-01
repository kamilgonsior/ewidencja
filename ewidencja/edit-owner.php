<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # If owner ID is not set
    if (!isset($_GET['id'])) {
        #Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];

    # Database Connection File
    include "db_conn.php";

    # Owner helper function
    include "php/func-owner.php";
    $owner = get_owner($conn, $id);

    # If the ID is invalid
    if ($owner == 0) {
        #Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edytuj właściciela</title>

        <!-- bootstrap 5-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

        <!-- bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    </head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin.php">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="collapse navbar-collapse" 
                        id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"> 
                        <a class="nav-link" 
                            aria-current="page" 
                            href="index.php">Strona główna</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" 
                            href="add-device.php">Dodaj sprzęt</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" 
                            href="add-category.php">Dodaj kategorię</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" 
                            href="add-owner.php">Dodaj właściciela</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" 
                            href="logout.php">Wylogowanie</a>
                        </li>
                        </ul>
                    </div>
            </div>
        </nav>
    <form action="php/edit-owner.php"
          method="post"
          class="shadow p-4 rounded mt-5"
          style="width: 90%; max-width: 50rem;">

        <h1 class="text-center pb-5 display-4 fs-3">
            Edytuj właściciela
        </h1>
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?=htmlspecialchars($_GET['error']); ?>
            </div>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success" role="alert">
                <?=htmlspecialchars($_GET['success']); ?>
            </div>
        <?php } ?>
        <div class="mb-3">
                <label class="form-label">Właściciel</label>

                <input type="text" 
                       value="<?=$owner['id'] ?>"
                       hidden
                       name="owner_id">

                <input type="text" 
                       class="form-control"
                       value="<?=$owner['name'] ?>"
                       name="owner_name">
        </div>

        <button type="submit" 
                class="btn btn-primary">
                Aktualizuj właściciela</button>
    </form>    
    </div>
</body>
</html> 

<?php }else{
    header("Location: login.php");
    exit;
} ?>