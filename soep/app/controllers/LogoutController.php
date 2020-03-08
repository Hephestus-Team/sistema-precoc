<?php
    require "../../../vendor/autoload.php";
    use \Manager\User;

    session_start();

    if(User::logout()){
        print true;
    }else{
        print false;
    }