<?php
##########################################################################
#  Herbology 
#  Body Class
#  Parts of the body effected by the herb
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.body_sql.inc.php');

class body
{

    var $dO;   //data object
    
    public function __construct()
    {
        $this->dO = new body_sql;
    }

    function body()
    {
        self::__construct();
    }

		
    function get_bodies()
    {
        return $this->dO->get_bodies();
    }

    function get_herb_bodies($herbID)
    {

        return $this->dO->get_herb_bodies($herbID);

    }		

}

