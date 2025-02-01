<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

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
        <title>Admin</title>

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
        <div class="mt-5"></div>
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


        <?php if ($devices == 0) { ?>
            <div class="alert alert-warning text-center p-5" role="alert">
                <img src="img/empty.png" witdh="100">
                <br>
                Nie ma sprzętu w bazie danych
            </div>
        <?php }else {?>

        <!-- List of all devices -->    
        <h4 class="mt-5">Wszystkie sprzęty</h4>
        <table class="table table-bordered shadow">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Model</th>
                    <th>Właściciel</th>
                    <th>Opis sprzętu</th>
                    <th>Kategoria </th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 0;
                foreach ($devices as $device) {
                    $i++;    
                ?>
                <tr>
                    <td><?=$i?></td> 
                    <td>
                        <img width="100"
                             src="uploads/cover/<?=$device['cover']?>" >
                        <a class="link-dark d-block text-center" href="uploads/files/<?=$device['file']?>">
                            <?=$device['device_type']?>
                        </a>
                    </td>
                    <td>
                        <?php if ($owners == 0) {
                            echo "Niezdefiniowany";}else{

                                foreach ($owners as $owner) {
                                    if ($owner['id'] == $device['owner_id']) {
                                        echo $owner['name'];

                                    }
                                }
                            }            
                        ?>
                    </td>
                    <td><?=$device['description']?></td>
                    <td>
                        <?php if ($categories == 0) {
                                echo "Niezdefiniowany";}else{

                                    foreach ($categories as $category) 
                                        {
                                        if ($category['id'] == $device['category_id']) {
                                            echo $category['name'];

                                        }
                                    }
                                }            
                        ?>
                    </td>
                    <td>
                        <a href="edit-device.php?id=<?=$device['id']?>" 
                           class="btn btn-warning">
                         Edytuj</a>

                        <a href="php/delete-device.php?id=<?=$device['id']?>" 
                        class="btn btn-danger">
                        Usuń</a>
                    </td> 
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php }?>

        <?php if ($categories == 0) { ?>
            <div class="alert alert-warning text-center p-5" role="alert">
                <img src="img/empty.png" witdh="100">
                <br>
                Nie ma kategorii w bazie danych
            </div>
        <?php }else {?>
        <!-- List of all categories -->    
        <h4 class="mt-5">Wszystkie kategorie</h4>
        <table class="table table-bordered shadow">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategoria sprzętu</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $j = 0;
                foreach ($categories as $category) {
                $j++;        
                ?>
                <tr>
                    <td><?=$j?></td>
                    <td><?=$category['name']?></td>
                    <td>
                        <a href="edit-category.php?id=<?=$category['id']?>" 
                           class="btn btn-warning">
                         Edytuj</a>

                        <a href="php/delete-category.php?id=<?=$category['id']?>" 
                        class="btn btn-danger">
                        Usuń</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

        <?php if ($owners == 0) { ?>
            <div class="alert alert-warning text-center p-5" role="alert">
                <img src="img/empty.png" witdh="100">
                <br>
                Nie ma właścicieli w bazie danych
            </div>
        <?php }else {?>
        <!-- List of all Owners -->    
        <h4 class="mt-5">Wszyscy właściciele</h4>
        <table class="table table-bordered shadow">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Właściciel</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $k = 0;
                foreach ($owners as $owner) {
                $k++;        
                ?>
                <tr>
                    <td><?=$k?></td>
                    <td><?=$owner['name']?></td>
                    <td>
                        <a href="edit-owner.php?id=<?=$owner['id']?>" 
                           class="btn btn-warning">
                         Edytuj</a>

                        <a href="php/delete-owner.php?id=<?=$owner['id']?>" 
                        class="btn btn-danger">
                        Usuń</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
</body>
</html> 

<?php }else{
    header("Location: login.php");
    exit;
} ?>