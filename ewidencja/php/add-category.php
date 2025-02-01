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
    if(isset($_POST['category_name'])){
        /**
         * Get data from POST request
         * and store in var
         */
        $name = $_POST['category_name'];

        # Simple form validation
        if (empty($name)) {
            $em = "Nazwa kategorii jest wymagana";
            header("Location: ../add-category.php?error=$em");
            exit;
        }else {
            # Insert into database
            $sql = "INSERT INTO categories (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$name]);

            /**
             * If there is no error while
             * inserting the data
             */
            if ($res) {
                # Success message
                $sm = "Pomyślnie dodano";
                header("Location: ../add-category.php?success=$sm");
                exit;
            }else {
                # Error message
                $em = "Nieznany błąd";
                header("Location: ../add-category.php?error=$em");
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