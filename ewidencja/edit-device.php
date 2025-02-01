<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # If device ID is not set
    if (!isset($_GET['id'])) {
        #Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];

    # Database Connection File
    include "db_conn.php";

    # Device helper function
    include "php/func-device.php";
    $device = get_device($conn, $id);

    # If the ID is invalid
    if ($device == 0) {
        #Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    # Category helper function
    include "php/func-category.php";
    $categories = get_all_categories($conn);

    # Owner helper function
    include "php/func-owner.php";
    $owners = get_all_owner($conn);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edytuj sprzęt</title>

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
    <form action="php/edit-device.php"
          method="post"
          enctype="multipart/form-data"
          class="shadow p-4 rounded mt-5"
          style="width: 90%; max-width: 50rem;">

        <h1 class="text-center pb-5 display-4 fs-3">
            Edytuj Sprzęt
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
                <label class="form-label">Model</label>
                <input type="text" 
                       class="form-control"
                       value="<?=$device['device_type']?>"
                       name="device_type">

                <input type="text" 
                       hidden
                       value="<?=$device['id']?>"
                       name="device_id">
        </div>

        <div class="mb-3">
                <label class="form-label">Opis sprzętu</label>
                <input type="text" 
                       class="form-control"
                       value="<?=$device['description']?>"
                       name="device_description">
        </div>

        <div class="mb-3">
                <label class="form-label">Właściciel</label>
                <select name="device_owner"
                    class="form-control">
                    <option value="0">
                        Wybierz właściciela
                    </option>
                    <?php
                        if ($owners == 0){
                            # Do nothing
                        }else{
                        foreach ($owners as $owner) { 
                            if ($device['owner_id'] == $owner['id'])
                                {
                            ?>
                            <option 
                            selected
                            value="<?=$owner['id']?>">
                            <?=$owner['name']?>
                        </option>
                        <?php }else{ ?>
                            <option
                                value="<?=$owner['id']?>">
                            <?=$owner['name']?>
                            </option>
                        <?php }} } ?>
                </select>       
        </div>

        <div class="mb-3">
                <label class="form-label">Kategoria</label>
                <select name="device_category"
                        class="form-control">
                        <option value="0">
                            Wybierz kategorię
                        </option>
                        <?php
                        if ($categories == 0){
                            # Do nothing
                        }else{
                            foreach ($categories as $category) { 
                                if ($device['category_id'] == $category['id'])
                                    {
                                ?>
                                <option 
                                selected
                                value="<?=$category['id']?>">
                                <?=$category['name']?>
                            </option>
                            <?php }else{ ?>
                                <option
                                    value="<?=$category['id']?>">
                                <?=$category['name']?>
                                </option>
                            <?php }} } ?>
                </select>
        </div>

        <div class="mb-3">
                <label class="form-label">Zdjęcie sprzętu</label>
                <input type="file" 
                       class="form-control"
                       name="device_cover">

                <input type="text" 
                       hidden
                       value="<?=$device['cover']?>"
                       name="current_cover">

                <a href="uploads/cover/<?=$device['cover']?>" 
                   class="link-dark"> Obecne zdjęcie</a>
        </div>

        <div class="mb-3">
                <label class="form-label">Opis</label>
                <input type="file" 
                       class="form-control"
                       name="file">

                <input type="text" 
                       hidden
                       value="<?=$device['file']?>"
                       name="current_file">

                <a href="uploads/files/<?=$device['file']?>" 
                   class="link-dark"> Obecny plik</a>
        </div>

        <button type="submit" 
                class="btn btn-primary">
                Aktualizuj sprzęt</button>
    </form>    
    </div>
</body>
</html> 

<?php }else{
    header("Location: login.php");
    exit;
} ?>