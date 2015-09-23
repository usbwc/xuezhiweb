<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 15/1/26
 * Time: 下午2:31
 */

class upload extends CI_Controller{

    public function index(){

        $targetFolder = '/uploads/temp'; // Relative to the root
        log_message('debug',print_r($_POST,true));
        //$verifyToken = md5('unique_salt' . $_POST['timestamp']);

        //if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
        log_message('debug',print_r($_FILES,true));
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

        $fileParts = pathinfo($_FILES['Filedata']['name']);


        $newName = md5($_FILES['Filedata']['name']).'.'.$fileParts['extension'];
        $targetFile = rtrim($targetPath,'/') . '/' .$newName ;

        // Validate the file type
        $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
        log_message('debug',print_r($fileParts,true));
        if (in_array($fileParts['extension'],$fileTypes)) {
            move_uploaded_file($tempFile,$targetFile);
            echo $targetFolder.'/'.$newName;
        } else {
            echo 'Invalid file type.';
        }
        //}

    }
} 