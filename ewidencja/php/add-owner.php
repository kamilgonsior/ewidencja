<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # Database Connection File
    include "../db_conn.php";


    /**
     * Check if owner
     * name is submitted
    */    
    if (isset($_POST['owner_name'])) {
        /**
         * Get data from POST request
         * and store in var
         */
        $name = $_POST['owner_name'];

        # Simple form validation
        if (empty($name)) {
            $em = "Dane właściciela są wymagane";
            header("Location: ../add-owner.php?error=$em");
            exit;
        }else {
            # Insert into databse
            $sql = "INSERT INTO owners (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$name]);

            /**
             * If there is no error while
             * inserting the data
             */
            if ($res) { 
                # Success message
                $sm = "Pomyślnie dodano";
                header("Location: ../add-owner.php?success=$sm");
                exit;     
            }else{
                # Error message
                $em = "Nieznany błąd";
                header("Location: ../add-owner.php?error=$em");
                exit;
            }
        }
    }else {
        header("Location: ../admin.php");
        exit;       
    }

}else{
    header("Location: ../login.php");
    exit;
}