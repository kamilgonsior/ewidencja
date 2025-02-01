<?php

# Get all devices function
function get_all_devices($con){
    $sql = "SELECT * FROM devices ORDER BY id DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $devices = $stmt->fetchAll();
    }else {
        $devices = 0;
    }

    return $devices;
}

# Get device by ID function
function get_device($con, $id){
    $sql = "SELECT * FROM devices WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $device = $stmt->fetch();
    }else {
        $device = 0;
    }

    return $device;
}

# Search devices function
function search_devices($con, $key){
    # creating simple search algorithm
    $key = "%{$key}%";

    $sql = "SELECT * FROM devices WHERE device_type LIKE ? OR description LIKE ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$key, $key]);

    if ($stmt->rowCount() > 0) {
        $devices = $stmt->fetchAll();
    }else {
        $devices = 0;
    }

    return $devices;
}

# Get devices by category
function get_devices_by_category($con, $id){
    $sql = "SELECT * FROM devices WHERE category_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $devices = $stmt->fetchAll();
    }else {
        $devices = 0;
    }

    return $devices;
}

# Get devices by owner
function get_devices_by_owner($con, $id){
    $sql = "SELECT * FROM devices WHERE owner_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $devices = $stmt->fetchAll();
    }else {
        $devices = 0;
    }

    return $devices;
}