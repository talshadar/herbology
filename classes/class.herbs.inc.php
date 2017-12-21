<?php
##########################################################################
#  Herbology
#  Herb class
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################
// Report all PHP errors (see changelog)
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');

require_once('class.herbs_sql.inc.php');

class herbs
{

    var $dO;   //data object

    public function __construct() {
        $this->dO = new herbs_sql;;
    }

    function herbs()
    {
        //$this->dO = new herbs_sql;
        self::__construct();
    }

    function get_herbs($filter="", $order="")
    {
        return $this->dO->get_herbs($filter, $order);
    }

    function get_herb($herbID)
    {
        return $this->dO->get_herb($herbID);
    }

    function get_herbImages($herbID)
    {
        return $this->dO->get_herbImages($herbID);
    }		

    function update_herb($data)
    {
        return $this->dO->update_herb($data);
    }

    function save_herb($data)
    {
        return $this->dO->save_herb($data);
    }

    function save_herb_images($data)
    {
        return $this->dO->save_herb_images($data);
    }

}

?>