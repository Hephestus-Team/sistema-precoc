<?php
    namespace Manager;
    use \Manager\DB;
    use PDO;

    class User
    {
        public $dbManager;
        public const PROF = 0;
        public const ADM = 1;

        /**
         * Função geradora de senha aletoria
         */
        public static function passwordGenerator(?int $range = 6) : string
        {
            $alphabet = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
            $password = "";
            for ($i = 0; $i < 6; $i++) {
                $n = rand(0, (count($alphabet) - 1));
                $password .= $alphabet[$n];
            }
            return $password;
        }

        /**
         * Função para envio de email para o professor
         */
        public static function sendMail(string $email, string $password)
        {
            $subject = 'XXX'; 
            $headers = 'From: XXX@XXX.com' . "\r\n";
            $headers .= 'Content-type: text/html;' . "\r\n"; 
            $default = "XXX <b>$password</b>";

            if(!@mail($email, $subject, $default, $headers)){
                return false;
            }else{
                return true;
            }
        }

        /**
         * Função de logout
         */
        public function logout() : bool
        {
            if(session_destroy()){
                return true;
            }else{
                return false;
            }
        }

        /**
         * Função para validação das credenciais
         */
        public static function login(string $user, string $input_pass, int $cod)
        {
            $dbManager = new DB('administrador');

            if($cod == 0){
                $check = $dbManager->DQL(
                    ['matricula, nome'],
                    ['cadastro_professor'],
                    'matricula = :matricula OR email = :email',
                    ['matricula' => $user, 'email' => $user],
                    PDO::FETCH_ASSOC
                );
    
                if($check != null){
                    $pass = $dbManager->DQL(
                        ['senha'],
                        ['cadastro_professor'],
                        'matricula = :matricula OR email = :email',
                        ['matricula' => $user, 'email' => $user],
                        PDO::FETCH_BOTH
                    );

                    if(password_verify($input_pass, $pass[0]['senha'])){
                        return $check[0]['nome'];
                    }else{
                        return false;
                    }

                }else{
                    return false;
                }

            }elseif($cod == 1){
                $check = $dbManager->DQL(
                    ['usuario'],
                    ['cadastro_administrador'],
                    'usuario = :usuario',
                    ['usuario' => $user],
                    PDO::FETCH_ASSOC
                );
    
                if($check != null){
                    $pass = $dbManager->DQL(
                        ['senha'],
                        ['cadastro_administrador'],
                        'usuario = :usuario',
                        ['usuario' => $user],
                        PDO::FETCH_BOTH
                    );

                    if(password_verify($input_pass, $pass[0]['senha'])){
                        return $check[0]['usuario'];
                    }else{
                        return false;
                    }

                }else{
                    return false;
                }

            }else{
                print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): 3rd ARGUMENT INVALID <$cod>, ADM or PROF CONSTS EXPECTED<br></pre>";
            }
        }
    }