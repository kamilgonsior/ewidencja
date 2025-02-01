<?php
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

    # Database Connection File
    include "../db_conn.php";

    # Validation helper function
    include "func-validation.php";

    # File upload helper function
    include "func-file-upload.php";
    

    /**
     * If all Input fields
     * are filled
    */    
    if(isset($_POST['device_id']) &&
       isset($_POST['device_type']) &&
       isset($_POST['device_description']) &&
       isset($_POST['device_owner']) &&
       isset($_POST['device_category']) &&
       isset($_FILES['device_cover']) &&
       isset($_FILES['file']) &&
       isset($_POST['current_cover']) &&
       isset($_POST['current_file'])) {
        /**
         * Get data from POST request
         * and store in var
         */
        $id = $_POST['device_id'];
        $type = $_POST['device_type'];
        $description = $_POST['device_description'];
        $owner = $_POST['device_owner'];
        $category = $_POST['device_category'];

        /**
         * Get current cover & current file
         * from POST request and store them in var
         */

        $current_cover = $_POST['current_cover'];
        $current_file = $_POST['current_file'];

        # Simple form validation

        $text = "Model";
        $location = "../edit-device.php";
        $ms = "id=$id&error";
        is_empty($type, $text, $location, $ms, "");

        $text = "Opis sprzętu";
        $location = "../edit-device.php";
        $ms = "id=$id&error";
        is_empty($description, $text, $location, $ms, "");

        $text = "Właściciel";
        $location = "../edit-device.php";
        $ms = "id=$id&error";
        is_empty($owner, $text, $location, $ms, "");

        $text = "Kategoria";
        $location = "../edit-device.php";
        $ms = "id=$id&error";
        is_empty($category, $text, $location, $ms, "");

        /**
         * If the admin tries to 
         * update the device cover
         */
        if (!empty($_FILES['device_cover']['name'])){
            
            /**
             * If the admin tries to
             * update them both
             */
            if (!empty($_FILES['file']['name'])){
                # Update both

                # device cover uploading
                $allowed_image_exs = array("jpg", "jpeg", "png");
                $path = "cover";
                $device_cover = upload_file($_FILES['device_cover'], $allowed_image_exs, $path);

                # device file uploading
                $allowed_file_exs = array("pdf", "docx", "ppx");
                $path = "files";
                $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

                /**
                 * If error occured while
                 * uploading
                 */

                if ($device_cover['status'] == "error" || $file['status'] == "error") {

                    $em = $device_cover['data'];
        
                    /**
                     * Redirect to '../edit-device.php'
                     * and pass error message & the id
                     */
                    header("Location: ../edit-device.php?error=$em&id=$id");
                    exit;
                }else {
                    # current device cover location
                    $c_p_device_cover = "../uploads/cover/$current_cover";

                    # current file path
                    $c_p_file = "../uploads/files/$current_file";

                    # Delete from the server
                    unlink($c_p_device_cover);
                    unlink($c_p_file);

                    /**
                     * Getting the new file name
                     * and the new device cover name
                     */

                    $file_URL = $file['data'];
                    $device_cover_URL = $device_cover['data'];

                    # Update just the data
                    $sql = "UPDATE devices SET device_type=?, owner_id=?, description=?, category_id=?, cover=?, file=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $res = $stmt->execute([$type, $owner, $description, $category, $device_cover_URL, $file_URL, $id]);

                        /**
                        * If there is no error while
                        * updating the data
                        */
                        if ($res) {
                            # Success message
                            $sm = "Pomyślnie zaktualizowano";
                            header("Location: ../edit-device.php?success=$sm&id=$id");
                            exit;
                        }else {
                            # Error message
                            $em = "Nieznany błąd";
                            header("Location: ../edit-device.php?error=$em&id=$id");
                            exit;
                        }
                }
            }else {
                # Update just the device cover

                # device cover uploading
                $allowed_image_exs = array("jpg", "jpeg", "png");
                $path = "cover";
                $device_cover = upload_file($_FILES['device_cover'], $allowed_image_exs, $path);

                /**
                 * If error occured while
                 * uploading
                 */

                if ($device_cover['status'] == "error") {

                    $em = $device_cover['data'];
        
                    /**
                     * Redirect to '../edit-device.php'
                     * and pass error message & the id
                     */
                    header("Location: ../edit-device.php?error=$em&id=$id");
                    exit;
                }else {
                    # current device cover location
                    $c_p_device_cover = "../uploads/cover/$current_cover";

                    # Delete from the server
                    unlink($c_p_device_cover);

                    /**
                     * Getting the new file name
                     * and the new device cover name
                     */

                    $device_cover_URL = $device_cover['data'];

                    # Update just the data
                    $sql = "UPDATE devices SET device_type=?, owner_id=?, description=?, category_id=?, cover=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $res = $stmt->execute([$type, $owner, $description, $category, $device_cover_URL, $id]);

                        /**
                        * If there is no error while
                        * updating the data
                        */
                        if ($res) {
                            # Success message
                            $sm = "Pomyślnie zaktualizowano";
                            header("Location: ../edit-device.php?success=$sm&id=$id");
                            exit;
                        }else {
                            # Error message
                            $em = "Nieznany błąd";
                            header("Location: ../edit-device.php?error=$em&id=$id");
                            exit;
                        }
                }
            }
        }
        /**
         * If the admin tries to 
         * update just the file
         */
        else if (!empty($_FILES['file']['name'])){
            # Update just the file

            $allowed_file_exs = array("pdf", "docx", "pptx");
                $path = "files";
                $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

                /**
                 * If error occured while
                 * uploading
                 */

                if ($file['status'] == "error") {

                    $em = $file['data'];
        
                    /**
                     * Redirect to '../edit-device.php'
                     * and pass error message & the id
                     */
                    header("Location: ../edit-device.php?error=$em&id=$id");
                    exit;
                }else {
                    # current device cover location
                    $c_p_file = "../uploads/files/$current_file";

                    # Delete from the server
                    unlink($c_p_file);

                    /**
                     * Getting the new file name
                     * and the new device file name
                     */

                    $file_URL = $file['data'];

                    # Update just the data
                    $sql = "UPDATE devices SET device_type=?, owner_id=?, description=?, category_id=?, file=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $res = $stmt->execute([$type, $owner, $description, $category, $file_URL, $id]);

                        /**
                        * If there is no error while
                        * updating the data
                        */
                        if ($res) {
                            # Success message
                            $sm = "Pomyślnie zaktualizowano";
                            header("Location: ../edit-device.php?success=$sm&id=$id");
                            exit;
                        }else {
                            # Error message
                            $em = "Nieznany błąd";
                            header("Location: ../edit-device.php?error=$em&id=$id");
                            exit;
                        }
                }
        }else {
            # Update just the data
            $sql = "UPDATE devices SET device_type=?, owner_id=?, description=?, category_id=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$type, $owner, $description, $category, $id]);

            /**
             * If there is no error while
             * updating the data
             */
            if ($res) {
                # Success message
                $sm = "Pomyślnie zaktualizowano";
                header("Location: ../edit-device.php?success=$sm&id=$id");
                exit;
            }else {
                # Error message
                $em = "Nieznany błąd";
                header("Location: ../edit-device.php?error=$em&id=$id");
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