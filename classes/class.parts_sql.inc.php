<?php

##########################################################################
#  Herbology
#  Parts class
#  Parts of the herb used (root, leaf ...)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

  require_once('class.dbcon.php');
	class parts_sql
	{
		var $db;

		function parts_sql()
		{
			$this->db = new DBCon('localhost', 'bruttle_herbadmn', 'passwd', 'bruttle_herbology');
//			$this->db->connect;
		}
		
		function get_parts()
		{
			
			$sql = "select * from parts ";
      //echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;			
		}

		function get_herb_parts($herbID)
		{
		
			$sql = "SELECT parts.term, parts.definition, parts.parts_index ";
      $sql .= "FROM parts "; 
      $sql .= "INNER JOIN herb_parts ";
      $sql .= "ON parts.parts_index = herb_parts.parts_index ";
      $sql .= "where herb_parts.herb_index = " . $herbID;
			
			//echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;
		
		}		
		
  }//end clas


?>