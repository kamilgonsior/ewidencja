<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # Database Connection File
    include "../db_conn.php";
    

    /**
     * Check if category
     * name is submitted
    */    
    if(isset($_POST['category_name']) &&
       isset($_POST['category_id'])) {
        /**
         * Get data from POST request
         * and store in var
         */
        $name = $_POST['category_name'];
        $id = $_POST['category_id'];

        # Simple form validation
        if (empty($name)) {
            $em = "Nazwa kategorii jest wymagana";
            header("Location: ../edit-category.php?error=$em&id=$id");
            exit;
        }else {
            # Insert the database
            $sql = "UPDATE categories SET name=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$name, $id]);

            /**
             * If there is no error while
             * updating the data
             */
            if ($res) {
                # Success message
                $sm = "Pomyślnie zaktualizowano";
                header("Location: ../edit-category.php?success=$sm&id=$id");
                exit;
            }else {
                # Error message
                $em = "Nieznany błąd";
                header("Location: ../edit-category.php?error=$em&id=$id");
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