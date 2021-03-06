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
    
    public function __construct()
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
        return $this->dO->get_herb_properties($herbID);
    }
    
    function get_property($propID)
    {
        return $this->dO->get_property($propID);
    }
}
