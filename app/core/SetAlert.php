<?php 
class setAlert {
    public static function alert($header, $description, $type, $action) {
        $_SESSION['alert'] = [
            'header' => $header,
            'description' => $description,
            'type' => $type,
            'action' => $action
        ];
    }

    public static function deleteAlert() {
        if(isset($_SESSION['alert'])) {
            unset($_SESSION['alert']);
        }
    }
}