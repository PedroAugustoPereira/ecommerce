<?php
    namespace Page;

    use Rain\Tpl;

    class Page{

        private $tpl;
        private $options = [];
        private $defaults = [
            "header"=> true,
            "footer"=>true,
            "data" => []
        ];

        public function __construct($opts = array(),  $tpl_dir = "/views/")
        {
            //$this->defaults["data"]["session"] = $_SESSION;


            $this->options = array_merge($this->defaults, $opts);

            $config = array(
                "tpl_dir"       => $_SERVER['DOCUMENT_ROOT']. $tpl_dir,
                "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/ecomerce/views-cache/",
                "debug"         => false
            );

            

            Tpl::configure($config);

            $this->tpl = new Tpl;

            $this->setData($this->options["data"]);



            if($this->options["header"]){
                $this->tpl->draw("header", false);
            }
           
        }

        private function setData($data = array()){
            foreach($data as $key => $value){
                $this->tpl->assign($key, $value);
            }
        }


        public function setTpl($name, $data = array(), $returnHTML = false){
            $this->setData($data);

            return $this->tpl->draw($name, $returnHTML);
        }
        

        


        public function __destruct()
        {
            if($this->options["footer"] === true){
                
                $this->tpl->draw("footer", false);
            }
        }


    }


?>