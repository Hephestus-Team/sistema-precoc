<?php
    ## CONTROLLER QUE GERENCIA O LOGIN DO USUARIO SOEP ##
    require "../../../vendor/autoload.php";
    use \Manager\User;

    $check = User::login($_POST['usuario'], $_POST['senha'], User::ADM);

    if($check){

        session_start();
        $_SESSION['logged_adm'] = true;
        $_SESSION['user'] = $check;
        
        print 'true';
    }else{
        print $check;
    }