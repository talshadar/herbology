<?php

##########################################################################
#  Herbology 
#  properties class
#  Properties of the herb (antiviral, expectorant...)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

  require_once('class.dbcon.php');
	class properties_sql
	{
		var $db;

		function properties_sql()
		{
			$this->db = new DBCon('localhost', 'herbadmn', 'passwd', 'herbology');
//			$this->db->connect;
		}
		
		function get_properties()
		{
			
			$sql = "select * from properties ";
			$sql .= " order by term ";
      //echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;			
		}
		
		function get_herb_properties($herbID)
		{
		
			$sql = "SELECT properties.term, properties.definition, properties.properties_index ";
      $sql .= "FROM properties "; 
      $sql .= "INNER JOIN herb_properties ";
      $sql .= "ON properties.properties_index = herb_properties.properties_index ";
      $sql .= "where herb_properties.herb_index = " . $herbID;
			//$sql .= " order by properties.term ";
			//echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;
		
		}

  }//end clas
	
/*
SELECT properties.term 
FROM properties 
INNER JOIN herb_properties
ON properties.properties_index = herb_properties.properties_index 
where herb_properties.herb_index = 1 */

?>

