<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    use Manager\User;
    $dbManager_adm = new DB('administrador');
    $dbManager_precoc =  new DB('precoc');

    $professores = $dbManager_adm->DQL(
        ['email', 'nome'], 
        ['professores'], 
        "matricula = :matricula",
        ['matricula' => $_POST['matricula']],
        PDO::FETCH_BOTH
    );
    $cadastro_professor = $dbManager_adm->DQL(
        ['email'],
        ['cadastro_professor'],
        "matricula = :matricula",
        ['matricula' => $_POST['matricula']],
        PDO::FETCH_BOTH
    );

    if($professores != null){
        if($cadastro_professor == null){
            $crude = User::passwordGenerator();
            $hash = password_hash($crude, PASSWORD_DEFAULT, ['cost' => 12]);

            if(User::sendMail($professores[0][0], $crude)){
                $dbManager_adm->DML(
                    ['cadastro_professor'],
                    ['matricula', 'nome', 'email', 'senha'],
                    ':matricula, :nome, :email, :senha',
                    ['matricula' => $_POST['matricula'], 'nome' => $professores[0][1], 'email' => $professores[0][0], 'senha' => $hash],
                    DB::INSERT
                );

                $dbManager_precoc->DML(
                    ['professor'],
                    ['matricula', 'nome'],
                    ':matricula, :nome',
                    ['matricula' => $_POST['matricula'], 'nome' => $professores[0][1]],
                    DB::INSERT
                );
                print true;
            }else{
                print 'Erro no sistema de e-mail';
            }
        }else{
            print 'Já foi feito seu cadastro, consulte seu email para mais informações';
        }
    }else{
        print 'E-mail não cadastrado no sistema, entre em contato com o SEOP';
    }