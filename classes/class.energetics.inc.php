<?php
##########################################################################
#  Herbology 
#  Energetics class
#  Spectrum of the herb ( cooling, warming, drying...)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.energetics_sql.inc.php');

class energetics
{

 		var $dO;   //data object

		function energetics()
		{
			$this->dO = new energetics_sql;
		}
		
		function get_energetics()
		{
			return $this->dO->get_energetics();
		}

		function get_herb_energetics($herbID)
		{
			return $this->dO->get_herb_energetics($herbID);
		}
		
}

?>