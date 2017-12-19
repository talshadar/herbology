<?php
##########################################################################
#  Herbology 
#  Formulas class
#  Formula as well as all components related to (parts, actions etc)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.formulas_sql.inc.php');

class formulas
{

 		var $dO;   //data object

		function formulas()
		{
			$this->dO = new formulas_sql;
		}
		
		function get_formulas()
		{
			  return $this->dO->get_formulas();
		}
		
		function get_formula($formulaID)
		{
	
		    return $this->dO->get_formula($formulaID);
		}
			
		function get_formula_preparations($formulaID)
		{
		     return $this->dO->get_formula_preparations($formulaID);
		}
		
		function get_formula_herbs($formulaID)
		{
		    return $this->dO->get_formula_herbs($formulaID);
		}		
		
		function get_herb_actions($actionID)
		{
		 		return $this->dO->get_herb_actions($actionID);
		}
		
		function get_actions()
		{
		    return $this->dO->get_actions();
		}
		
		function get_preparations()
		{
		    return $this->dO->get_preparations();		
		}
		
		function get_herb_formulas($herbID)
		{
		  //echo "first function";
			return $this->dO->get_herb_formulas($herbID);
		}
		
		function save_formula($data)
		{
		   return $this->dO->save_formula($data);
		}
		
}

?>