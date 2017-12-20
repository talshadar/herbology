<?php
##########################################################################
#  Herbology 
#  Account Class
#  
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

  require_once('class.dbcon.php');
	class account_sql
	{
		var $db;

		function account_sql()
		{
			$this->db = new DBCon('localhost', 'herbadmn', 'passwd', 'herbology');
//			$this->db->connect;
		}
		
		function checkLogin($login, $pass)
		{
			
			$sql = "select account_Index, name from account where login = '" . $login . "' and passwd = password('" . $pass . "')";
      //echo "<br>SQL:" . $sql;
			
			$result = $this->db->fetch_from_db($sql);
			return $result;			
		}

  }//end clas


?>