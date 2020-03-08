<?php
    ## CONTROLLER QUE GERENCIA A SESSÃO ##
    session_start();
    if(isset($_SESSION['logged_adm'])){
        print 'true';
    }else{
        print 'false';
    }