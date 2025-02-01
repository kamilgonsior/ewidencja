<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # Database Connection File
    include "../db_conn.php";
    

    /**
     * Check if the category
     * id is set
    */    
    if(isset($_GET['id'])) {
        /**
         * Get data from GET request
         * and store in var
         */
        $id = $_GET['id'];

        # Simple form validation
        if (empty($id)) {
            $em = "Wystąpił błąd";
            header("Location: ../admin.php?error=$em");
            exit;
        }else {
            # DELETE the category from the database
            $sql = "DELETE FROM categories WHERE id=?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$id]);

            /**
            * If there is no error while
            * deleting the data
            */
            if ($res) {
                # Success message
                $sm = "Pomyślnie usunięto";
                header("Location: ../admin.php?success=$sm");
                exit;
            }else{
                $em = "Wystąpił błąd";
                header("Location: ../admin.php?error=$em");
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