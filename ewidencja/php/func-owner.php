<?php

# Get all Owner function
function get_all_owner($con){
    $sql = "SELECT * FROM owners";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $owners = $stmt->fetchAll();
    }else {
        $owners = 0;
    }

    return $owners;
}


# Get Owner by ID function
function get_owner($con, $id){
    $sql = "SELECT * FROM owners WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $owner = $stmt->fetch();
    }else {
        $owner = 0;
    }

    return $owner;
}