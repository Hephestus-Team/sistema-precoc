<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    use Manager\User;
    $dbManager = new DB('administrador');
    session_start();

    if(User::login($_POST['user'], $_POST['senha'], User::PROF) == true){
        $matricula = $dbManager->DQL(
            ['matricula'],
            ['cadastro_professor'],
            'matricula = :matricula OR email = :email',
            ['matricula' => $_POST['user'], 'email' => $_POST['user']],
            PDO::FETCH_BOTH
        );
        
        $_SESSION['logged'] = true;
        $_SESSION['matricula'] = $matricula[0]['matricula'];
        
        print 'true';
    }else{
        print 'false';
    }