<?php
session_start();

if (isset($_POST['email']) &&
    isset($_POST['password'])) {

    # Database Connection File
    include "../db_conn.php";
    
    # Validation helper function
    include "func-validation.php";

    /**
    *    Get data from POST and store in var
    */

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    #Simple form validation

    $text = "Email";
    $location = "../login.php";
    $ms = "error";
    is_empty($email, $text, $location, $ms, "");

    $text = "Hasło";
    $location = "../login.php";
    $ms = "error";
    is_empty($password, $text, $location, $ms, "");

    #search for the email
    $sql = "SELECT * FROM admin 
            WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    #if the email exists
    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();

        $user_id = $user['id'];
        $user_email = $user['email'];
        $user_password = $user['password'];
        if ($email === $user_email) {
            if (password_verify($password, $user_password)){
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $user_email;
                header("Location: ../admin.php");
            }else {
                # Error message
                $em = "Błędna nazwa albo hasło";
                header("Location: ../login.php?error=$em");
            }
        }else {
            # Error message
            $em = "Błędna nazwa albo hasło";
            header("Location: ../login.php?error=$em");
        }
    }else{
        # Error message
        $em = "Błędna nazwa albo hasło";
        header("Location: ../login.php?error=$em");
    }
}else {
    # Redirect to "../login.php"
    header("Location: ../login.php");
}
