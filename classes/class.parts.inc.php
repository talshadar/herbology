<?php
##########################################################################
#  Herbology
#  Parts class
#  Parts of the herb used (root, leaf ...)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.parts_sql.inc.php');

class parts
{

    var $dO;   //data object
    
    public function __construct()
    {
        $this->dO = new parts_sql;
    }

    function parts()
    {
        self::__construct();
    }
    
    function get_parts()
    {
        return $this->dO->get_parts();
    }

    function get_herb_parts($herbID)
    {
        //echo "first function";
        return $this->dO->get_herb_parts($herbID);
    }
}

