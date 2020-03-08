<?php
    namespace Manager;

    /**
     * Classe usada para modificar os valores de um dado spreadsheet
     */
    class Spreadsheet
    {        
        public const COLUMN_STRING = 0;
        public const COLUMN_ARRAY = 1;

        /**
         * Função que retorna as colunas do spreadsheet em string ou array
         */
        static function getColumn(array $sheet, int $type)
        {
            $cell_alphabet = range('A', 'Z');
            if($type == 0){
                $key_string = "";
                for($i = 0; $i < count($sheet[1]); $i++){
                    $key_string .= strtolower($sheet[1][$cell_alphabet[$i]]);
                    if($i != count($sheet[1]) - 1){
                        $key_string .= ", ";
                    }
                }        

                return $key_string;                
            }elseif($type == 1){
                for($i = 0; $i < count($sheet[1]); $i++){
                    $key_array[] = strtolower($sheet[1][$cell_alphabet[$i]]);
                }      

                return $key_array;
            }else{
                print "<pre>[" . __CLASS__ . "](". __FUNCTION__ ."): 2rd ARGUMENT INVALID <$type>, COLUMN_STRING, COLUMN_ARRAY or COLUMN_LAZY CONSTS EXPECTED<br></pre>";
            }
        }

        /**
         * Função que retorna um array filtrado apartir de um spreadsheet
         */
        static function toArray(array $sheet, ?array $optional) : array
        {
            $cell_alphabet = range('A', 'Z');
            $key_count = count($sheet[1]);
            $cell_count = count($sheet);
            for($i = 2; $i <= $cell_count; $i++){
                for($z = 0; $z < $key_count; $z++){
                    $cell_array[$i-2][$optional[$z]] = iconv("ISO-8859-1", "UTF-8","{$sheet[$i][$cell_alphabet[$z]]}");
                }
            }    

            return $cell_array;
        }

        /**
         * Função que retorna uma string contendo os valores de um array para inserir no banco de dados
         */
        static function toString(array $sheet, array $column) : string
        {            
            $query = "";
            for($i = 0; $i < count($sheet); $i++){
                $value = "";
                for($z = 0; $z < count($column); $z++){
                    $temp = "\"{$sheet[$i][$column[$z]]}\"";
                    $value .= iconv("UTF-8", "ISO-8859-1","$temp");
                    if($z != count($column) - 1){
                        $value .= ", ";
                    }
                }
                $query .= "($value)";
                if($i != count($sheet) - 1){
                    $query .= ", ";
                }
            }

            return $query;
        }

    }