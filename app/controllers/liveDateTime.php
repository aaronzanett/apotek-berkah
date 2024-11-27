<?php 
class liveDateTime {
    public function getDate() {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('l, d F Y');
        echo $date;
    }
    
    public function getTime() {
        date_default_timezone_set('Asia/Jakarta');
        $time = date('H:i:s');
        echo $time;
    }
}