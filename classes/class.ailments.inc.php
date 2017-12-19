<?php
##########################################################################
#  Herbology 
#  Ailments class
#
#  Matthew Bryan
#  Nov 15, 2017
##########################################################################

require_once('class.ailments_sql.inc.php');

class ailments
{

 		var $dO;   //data object

		function ailments()
		{
			$this->dO = new ailments_sql;
		}
		
		function get_ailments()
		{
			return $this->dO->get_ailments();
		}
		
		function get_ailment($ailmentIndex)
		{
		  return $this->dO->get_ailment($ailmentIndex);
		}

		function get_herb_ailments($herbID)
		{
			return $this->dO->get_herb_ailments($herbID);
		}
		
		function get_ailment_herbs($ailmentID)
		{
			return $this->dO->get_ailment_herbs($ailmentID);
		}
		
		
}

?>