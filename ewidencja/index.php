<?php
session_start();

# Database Connection File
include "db_conn.php";     
    
# Device helper function
include "php/func-device.php";
$devices = get_all_devices($conn);

# Owner helper function
include "php/func-owner.php";
$owners = get_all_owner($conn);

# Category helper function
include "php/func-category.php";
$categories = get_all_categories($conn);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ewidencja</title>

        <!-- bootstrap 5-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

        <!-- bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="css/style.css">
    </head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Ewidencja sprzętu</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="collapse navbar-collapse" 
                        id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"> 
                                <a class="nav-link active" 
                                    aria-current="page" 
                                    href="index.php">Strona główna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" 
                                    href="#">Opis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" 
                                    href="#contact.php">Kontakt</a>
                            </li>
                            <li class="nav-item">
                                <?php if (isset($_SESSION['user_id'])) {?>
                                    <a class="nav-link" 
                                    href="admin.php">Admin</a>
                                <?php }else{ ?>
                                <a class="nav-link" 
                            href="login.php">Logowanie</a>
                            <?php } ?>
                        </li>
                        </ul>
                    </div>
            </div>
        </nav>
        <form action="search.php"
              method="get" 
              style="width: 100%; max-width: 30rem">

            <div class="input-group my-5">   
                <input type="text"  
                       class="form-control"
                       name="key" 
                       placeholder="Wyszukaj sprzęt..." 
                       aria-label="Wyszukaj sprzęt..." 
                       aria-describedby="basic-addon2">
                <button class="input-group-text btn btn-primary"
                        id="basic-addon2">
                        <img src="img/search.png" width="20">
                </button>
            </div>
        </form>
        <div class="d-flex pt-3">
            <?php if ($devices == 0) { ?>
                <div class="alert alert-warning text-center p-5" role="alert">
                    <img src="img/empty.png" witdh="100">
                    <br>
                    Nie ma sprzętu w bazie danych
                </div>
                    <?php }else{ ?>
                    <div class="pdf-list  d-flex flex-wrap">
                        <?php foreach ($devices as $device) { ?>
                        <div class="card m-1">
                            <img src="uploads/cover/<?=$device['cover']?>"
                                class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?=$device['device_type']?></h5>
                                <p class="card-text">
                                    <i><b>Właściciel:
                                        <?php foreach($owners as $owner){ 
                                            if ($owner['id'] == $device['owner_id']){
                                                echo $owner['name'];
                                                break;
                                            }  
                                        ?>

                                        <?php } ?>
                                    <br></b></i>
                                    <?=$device['description']?>
                                    <br><i><b>Kategoria:
                                        <?php foreach($categories as $category){ 
                                            if ($category['id'] == $device['category_id']){
                                                echo $category['name'];
                                                break;
                                            }  
                                        ?>

                                        <?php } ?>
                                    <br></b></i>
                                </p>
                                <a href="uploads/cover/<?=$device['cover']?>" class="btn btn-success">Otwórz</a>

                                <a href="uploads/files/<?=$device['file']?>" class="btn btn-primary" download="<?=$device['device_type']?>">Pobierz</a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
            <?php } ?>
            <div class="category">
                <!-- List of categories -->
                <div class="list-group">
                    <?php if ($categories == 0){
                        // do nothing
                    }else{ ?>
                    <a href="category.php"
                       class="list-group-item list-group-item-action active">Kategoria</a>
                       <?php foreach ($categories as $category) {?>

                    <a href="category.php?id=<?=$category['id']?>" 
                       class="list-group-item list-group-item-action"><?=$category['name']?></a>
                       <?php } } ?>
                </div>

                <!-- List of owners -->
                <div class="list-group mt-5">
                    <?php if ($owners == 0){
                    }else{ ?>
                    <a href="#"
                       class="list-group-item list-group-item-action active">Właściciel</a>
                       <?php foreach ($owners as $owner) {?>

                    <a href="owner.php?id=<?=$owner['id']?>" 
                       class="list-group-item list-group-item-action"><?=$owner['name']?></a>
                       <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
