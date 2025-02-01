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
    if(isset($_POST['owner_name']) &&
       isset($_POST['owner_id'])) {
        /**
         * Get data from POST request
         * and store in var
         */
        $name = $_POST['owner_name'];
        $id = $_POST['owner_id'];

        # Simple form validation
        if (empty($name)) {
            $em = "Dane właściciela są wymagane";
            header("Location: ../edit-owner.php?error=$em&id=$id");
            exit;
        }else {
            # Update the database
            $sql = "UPDATE owners SET name=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$name, $id]);

            /**
             * If there is no error while
             * updating the data
             */
            if ($res) {
                # Success message
                $sm = "Pomyślnie zaktualizowano";
                header("Location: ../edit-owner.php?success=$sm&id=$id");
                exit;
            }else {
                # Error message
                $em = "Nieznany błąd";
                header("Location: ../edit-owner.php?error=$em&id=$id");
                exit;
            }
        }
    }else{ 
        header("Location: ../admin.php");
        exit;   
    }

}else{
    header("Location: ../login.php");
    exit;
}