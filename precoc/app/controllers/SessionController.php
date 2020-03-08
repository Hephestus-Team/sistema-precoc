<?php
    session_start();
    if(isset($_SESSION['logged'])){
        print 'true';
    }else{
        print 'false';
    }