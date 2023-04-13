<?php

namespace Model;



class Model{

    private $values = [];

    public function __call($name, $args){
        
        $method = substr($name, 0, 3);
        $fieldName = substr($name, 3 , strlen($name));
        
        switch($method){
            case "get":
                return $this->values[$fieldName];
                break;
            case "set":
                $this->values[$fieldName] = $args[0];
                break;
        }
    }

    public function setData($data = array()){
        foreach($data as $key => $value){
            //key é o nome do valor
            //$value vai ser o valor do post

            //aqui criamos o set com nome e o valor é $value
            $this->{"set". $key}($value);
        }
    }

    public function getValues(){
        return $this->values;
    }
}




?>