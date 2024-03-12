<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {

    function __construct(){
        parent::__construct();  

    }

    public function apk(){                 
        $file_name="SterlingWilson.apk"; 
        $file = FCPATH . "/assets/uploads/".$file_name;     

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.android.package-archive');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        } else {
            show_404();
        }

    }
}