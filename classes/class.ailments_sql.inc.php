<?php

##########################################################################
#  Herbology 
#  Ailments class
#
#  Matthew Bryan
#  Nov 15, 2017
##########################################################################

require_once('class.dbcon.php');
class ailments_sql
{
    var $db;

    public function __construct()
    {
        $this->db = new DBCon();
    }

    function ailments_sql()
    {
        self::__construct();
    } 


    function get_ailments()
    {

        $sql = "select * from ailments ";
        $sql .= " order by ailment ";
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;			
    }

    function get_ailment($ailmentIndex)
    {

        $sql = "select * from ailments ";
        $sql .= " where ailment_index = " . $ailmentIndex;
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;			
    }

		
    function get_herb_ailments($herbID)
    {

        $sql = "SELECT ailments.ailment, ailments.description, ailments.ailment_index ";
        $sql .= "FROM ailments "; 
        $sql .= "INNER JOIN herb_ailments ";
        $sql .= "ON ailments.ailment_index = herb_ailments.ailment_index ";
        $sql .= "where herb_ailments.herb_index = " . $herbID;
        $sql .= " order by ailments.ailment ";
			
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;
		
    }
		
    function get_ailment_herbs($ailmentID)
    {

        $sql = "SELECT herbs.herb_index, herbs.herb, herbs.description ";
        $sql .= "FROM herbs "; 
        $sql .= "INNER JOIN herb_ailments ";
        $sql .= "ON herbs.herb_index = herb_ailments.herb_index ";
        $sql .= "where herb_ailments.ailment_index = " . $ailmentID;
        $sql .= " order by herbs.herb ";

        //echo "<br>SQL:" . $sql;
			
        $result = $this->db->fetch_from_db($sql);
        return $result;

    }		
		
}//end clas

