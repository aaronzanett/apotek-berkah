<?php
class Home extends Controller {
    // home user
    public function index() {
        $data['page'] = 'Home';
        $this->template('header-user');
    }
}