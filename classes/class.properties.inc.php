<?php
##########################################################################
#  Herbology 
#  properties class
#  Properties of the herb (antiviral, expectorant...)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.properties_sql.inc.php');

class properties
{

    var $dO;   //data object
    
    public function __contruct()
    {
        $this->dO = new properties_sql;
    }
    
    function properties()
    {
        self::__construct();
    }

    function get_properties()
    {
        return $this->dO->get_properties();
    }

    function get_herb_properties($herbID)
    {
        //echo "first function";
        return $this->dO->get_herb_properties($herbID);
    }
}

?>