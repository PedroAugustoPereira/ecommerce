<?php

namespace pageAdmin;

class PageAdmin extends \Page\Page{
    
    public function __construct($opts = array(), $tpl_dir = "/views/admin/")
    {
    
        parent::__construct($opts, $tpl_dir);
        
    }
}


?>