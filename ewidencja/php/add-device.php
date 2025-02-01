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
     * If all input fields
     * are filled
    */      
    if (isset($_POST['device_type']) &&
        isset($_POST['device_description']) &&
        isset($_POST['device_owner']) &&
        isset($_POST['device_category']) &&
        isset($_FILES['device_cover']) &&
        isset($_FILES['file'])) {
        /**
         * Get data from POST request
         * and store them in var
         */
        $type = $_POST['device_type'];
        $description = $_POST['device_description'];
        $owner = $_POST['device_owner'];
        $category = $_POST['device_category'];

        # making URL data format
        $user_input = 'type='.$type.'&category_id='.$category.'&desc='.$description.'&owner_id='.$owner;

        # Simple form validation

        $text = "Model";
        $location = "../add-device.php";
        $ms = "error";
        is_empty($type, $text, $location, $ms, $user_input);

        $text = "Opis sprzętu";
        $location = "../add-device.php";
        $ms = "error";
        is_empty($description, $text, $location, $ms, $user_input);

        $text = "Właściciel";
        $location = "../add-device.php";
        $ms = "error";
        is_empty($owner, $text, $location, $ms, $user_input);

        $text = "Kategoria";
        $location = "../add-device.php";
        $ms = "error";
        is_empty($category, $text, $location, $ms, $user_input);

        # device cover uploading
        $allowed_image_exs = array("jpg", "jpeg", "png");
        $path = "cover";
        $device_cover = upload_file($_FILES['device_cover'], $allowed_image_exs, $path);

        /**
         * If error occured while
         * uploading the device cover
         */
        if ($device_cover['status'] == "error") {
            $em = $device_cover['data'];

            /**
             * Redirect to '../add-device.php'
             * and pass error message & user_input
             */
            header("Location: ../add-device.php?error=$em&$user_input");
            exit;
        }else {
                
            # file uploading
        $allowed_file_exs = array("pdf", "docx", "pptx");
        $path = "files";
        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

            /**
            * If error occured while
            * uploading the file
            */
            if ($file['status'] == "error") {
                $em = $file['data'];

                /**
                 * Redirect to '../add-device.php'
                 * and pass error message & user_input
                 */
                header("Location: ../add-device.php?error=$em&$user_input");
                exit;
        }else {
            /**
             * Getting the new file name
             * and device name
             */
            $file_URL = $file['data'];
            $device_cover_URL = $device_cover['data'];

            # Insert the data into database
            $sql = "INSERT INTO devices (device_type, owner_id, description, category_id, cover, file) VALUES (?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$type, $owner, $description, $category, $device_cover_URL, $file_URL]);

            /**
             * If there is no error while
             * inserting the data
             */
            if ($res) { 
                # Success message
                $sm = "Pomyślnie dodano nowe urządzenie";
                header("Location: ../add-device.php?success=$sm");
                exit;     
            }else{
                # Error message
                $em = "Nieznany błąd";
                header("Location: ../add-device.php?error=$em");
                exit;
            }
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