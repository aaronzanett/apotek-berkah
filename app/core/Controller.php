<?php
class Controller {
    // property
    protected $insights = ['Tentang Aplikasi', 'Pusat Bantuan'];
    protected $insightlinks = ['tentang_aplikasi', 'pusat_bantuan'];

    // view
    public function view_headoffice($file, $data = []){
        require_once("app/views/headoffice/$file.php");
    }
    
    public function view_admin($file, $data = []){
        require_once("app/views/admin/$file.php");
    }

    public function view_user($file, $data = []){
        require_once("app/views/user/$file.php");
    }

    public function template($template, $data = []){
        require_once("app/views/templates/$template.php");
    }

    // model
    public function model($model){
        require_once "app/models/$model.php";
        return new $model;
    }
}