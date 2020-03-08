<?php
    namespace Manager;
    use PDO;
    use PDOException;
    use TypeError;
    
    /**
     * Classe usada para intermediar a utilização do PDO
     */
    class DB
    {
        public $database;
        public const INSERT = 'INSERT';
        public const UPDATE = 'UPDATE';
        public const DELETE = 'DELETE';
        public const LONG_INSERT = 'LONG_INSERT';
        public const TRUNCATE = 'TRUNCATE';
        public const TABLE = 'TABLE';
        public const USER = 'USER';
        private const LIMIT = '';

        public function __construct(string $dbname)
        {   
            $data = json_decode(file_get_contents(__DIR__ . '\ServerSettings.json'), true);
            try
            {
                $this->database = new PDO("mysql:host=localhost;dbname=$dbname", $data['dbconfig']['USER'], $data['dbconfig']['PASSWORD'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
            }
            catch(PDOException $e){
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                die();
            }
        }

        /**
         * Função que transforma mais de um array em uma string
         */
        private function stringifyValues(array $values)
        {
            for($i = 0; $i <= array_key_last($values); ++$i){
                $array[$i] = implode(',', $values[$i]);
            }
            return $array;
        }

        /**
         * Função que retorna a mensagem padrão de erro de Manager\DB
         */
        private function showError(string $function, string $file, string $line, string $message) : string
        {
            return "<pre><i>[". __CLASS__ ."]($function)</i> Error in $file at line <b>$line</b>: $message</pre>";
        }

        /**
         * Função necessária para decodificar os valores do banco de dados de ISO-8859-1 para UTF-8
         */
        static function encodeValues(array $value, string $key) : array
        {
            for($i = 0; $i < count($value); $i++){
                $value[$i]['nome'] = iconv("ISO-8859-1", "UTF-8","{$value[$i][$key]}");
            }
            return $value;
        }

        ##SELECT SEM WHERE##
        private function DQLAll(array $column, array $table, ?int $fetchmode = 4) : ?array
        {
            $placeholder = self::stringifyValues([$column, $table]);
            $model = "SELECT {$placeholder[0]} FROM {$placeholder[1]} " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->setFetchMode($fetchmode);
                $query->execute();
                $row = $query->fetchAll();
                    return $row;
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                die();
            }
        }

        ##SELECT COM WHERE##
        private function DQLWhere(array $column, array $table, ?string $where, ?array $params = [], ?int $fetchmode = 4) : ?array
        {
            $placeholder = self::stringifyValues([$column, $table]);
            $model = "SELECT {$placeholder[0]} FROM {$placeholder[1]} WHERE $where " . self::LIMIT;
            try
            {
                $query = $this->database->prepare($model);
                $query->setFetchMode($fetchmode);
                $query->execute($params);
                $row = $query->fetchAll();
                    return $row;
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                $backtrace = debug_backtrace();
                    #print "<pre>[" . __CLASS__ . "](". __FUNCTION__ .") ". $backtrace[count($backtrace)-1]['file'] ."| LINE:«". $backtrace[count($backtrace)-1]['line'] ."»}: $message<br></pre>";
                    print $this->showError(__FUNCTION__, $backtrace[count($backtrace)-1]['file'], $backtrace[count($backtrace)-1]['line'], $message);
                die();
            }
        }
    
        ##INSERIR##
        private function DMLInsert(array $table, ?array $column, ?string $values, array $params)
        {
            $placeholder = self::stringifyValues([$table, $column]);
            $model = "INSERT INTO {$placeholder[0]} ({$placeholder[1]}) VALUES ($values) " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](" . __FUNCTION__ . "): $message<br></pre>";
                die();
            }        
        }

        ##INSERIR COM SPREADSHEET##
        public function SpreadsheetInsert(string $table, ?string $column, string $values) : bool
        {
            $this->DDL($table);

            $model = "INSERT INTO `$table` ($column) VALUES $values " . self::LIMIT;
            try
            {
                $query = $this->database->prepare($model);
                $query->execute();

                return true;
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](" . __FUNCTION__ . "): $message<br></pre>";
                die();
            }        
        }

        ##UPDATE##
        private function DMLUpdate(array $table, array $valores_novos, array $valores_antigos)
        {
            $placeholder = self::stringifyValues([$table]);
            $model = "UPDATE {$placeholder[0]} SET {$valores_novos['where_novo']} WHERE {$valores_antigos['where_antigo']} " . self::LIMIT;
            $params = array_merge($valores_antigos['params_antigo'], $valores_novos['params_novo']);

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                die();
            }        
        }

        ##DELETE##
        private function DMLDelete(array $table, ?string $where, ?array $params)
        {
            $placeholder = self::stringifyValues([$table]);
            $model = "DELETE FROM {$placeholder[0]} WHERE {$where} " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
            }
            catch(PDOException $e){
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                die();
            }        
        }

        ##DMLLONGINSERT##
        private function DMLLongInsert(string $table, ?string $column, string $values)
        {
            $model = "INSERT INTO $table ($column) VALUES $values " . self::LIMIT;
            try
            {
                $query = $this->database->prepare($model);
                $query->execute();
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](" . __FUNCTION__ . "): $message<br>MODEL»»$model</pre>";
                die();
            }
        }

        ##TRUNCATE##
        private function DDLTruncate(string $table)
        {
            $model = "TRUNCATE TABLE $table " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute();
            }
            catch(PDOException $e){
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                die();
            }   
        }

        /**
         * Função intermediaria de DQLWhere e DQLAll.
         * DQLWhere(array $column, array $table, ?string $where, ?array $params = [], ?int $fetchmode = 4).
         * 
         * DQLAll(array $column, array $table, ?int $fetchmode = 4).
         */
        public function DQL()
        {
            if(func_num_args() == 3){
                try{
                    return $this->DQLAll(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                }catch(TypeError $e){
                    $message = $e->getMessage();
                        print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                    die();
                }
            }elseif(func_num_args() == 5){
                try{
                    return $this->DQLWhere(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
                }catch(TypeError $e){
                    $message = $e->getMessage();
                        print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                    die();
                }
            }else{
                print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): INVALID NUMBER OF ARGUMENTS<br></pre>";
                die();
            }
        }

        /**
         * Função intermediaria de DMLInsert, DMLDelete e DMLUpdate.
         * DMLInsert(array $table, array $column, string $values, array $params).
         * 
         * DMLDelete(array $table, ?string $where, ?array $params).
         * 
         * DMLUpdate(array $table, array $valores_novos, array $valores_antigos)
         * 
         * DMLLongInsert(string $table, string $column ,string $values)
         */
        public function DML()
        {
            if(func_num_args() == 5){
                if(func_get_arg(4) == 'INSERT'){
                    try{
                        return $this->DMLInsert(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
                    }catch(TypeError $e){
                        $message = $e->getMessage();
                            print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                        die();
                    }
                }else{                    
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): 5th ARGUMENT INVALID, INSERT CONST EXPECTED<br></pre>";
                    die();
                }
            }elseif(func_num_args() == 4){
                if(func_get_arg(3) == 'DELETE'){
                    try{
                        return $this->DMLDelete(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                    }catch(TypeError $e){
                        $message = $e->getMessage();
                            print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                        die();
                    }
                }elseif(func_get_arg(3) == 'UPDATE'){
                    try{
                        return $this->DMLUpdate(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                    }catch(TypeError $e){
                        $message = $e->getMessage();
                            print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                        die();
                    }
                }elseif(func_get_arg(3) == 'LONG_INSERT'){
                    try{
                        return $this->DMLLongInsert(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                    }catch(TypeError $e){
                        $message = $e->getMessage();
                            print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                        die();
                    }
                }else{
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): INVALID NUMBER OF ARGUMENTS<br></pre>";
                    die();
                }
            }
        }
        /**
         * Função intermediaria de DDLTruncate.
         * DDLTruncate(string $table).
         */
        public function DDL(){
            try{
                $this->DDLTruncate(func_get_arg(0));
            }catch(TypeError $e){
                $message = $e->getMessage();
                    print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): $message<br></pre>";
                die();
            }
        }
}