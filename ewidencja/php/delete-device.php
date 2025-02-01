<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # Database Connection File
    include "../db_conn.php";
    

    /**
     * Check if the device
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
            # Get device from the database
            $sql2 = "SELECT * FROM devices WHERE id=?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute([$id]);
            $the_device = $stmt2->fetch();

            if($stmt2->rowCount() > 0){
                # DELETE the device from the database
                $sql = "DELETE FROM devices WHERE id=?";
                $stmt = $conn->prepare($sql);
                $res = $stmt->execute([$id]);

                /**
                * If there is no error while
                * deleting the data
                */
                if ($res) {
                    # delete the current device_cover and the file
                    $cover = $the_device['cover'];
                    $file = $the_device['file'];
                    $c_d_c = "../uploads/cover/$cover";
                    $c_f = "../uploads/files/$cover";

                    unlink($c_d_c);
                    unlink($c_f);

                    # Success message
                    $sm = "Pomyślnie usunięto";
                    header("Location: ../admin.php?success=$sm");
                    exit;
                }else{
                    # Error message
                    $em = "Nieznany błąd";
                    header("Location: ../admin.php?error=$em");
                    exit;
                    }
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