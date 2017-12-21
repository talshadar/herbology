<?php

##########################################################################
#  Herbology 
#  Energetics class
#  Spectrum of the herb ( cooling, warming, drying...)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

  require_once('class.dbcon.php');
	class energetics_sql
	{
		var $db;

		function energetics_sql()
		{
			$this->db = new DBCon('localhost', 'herbadmn', 'passwd', 'herbology');
//			$this->db->connect;
		}
		
		function get_energetics()
		{
			
			$sql = "select * from energetics ";
			$sql .= " order by term ";
      //echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;			
		}
		
		function get_herb_energetics($herbID)
		{
		
			$sql = "SELECT energetics.term, energetics.definition, energetics.energetics_index ";
      $sql .= "FROM energetics "; 
      $sql .= "INNER JOIN herb_energetics ";
      $sql .= "ON energetics.energetics_index = herb_energetics.energetics_index ";
      $sql .= "where herb_energetics.herb_index = " . $herbID;
			$sql .= " order by energetics.term ";
			
			//echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;
		
		}

  }//end clas


?>