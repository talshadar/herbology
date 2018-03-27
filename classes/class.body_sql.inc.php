<?php
##########################################################################
#  Herbology 
#  Body Class
#  Parts of the body effected by the herb
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.dbcon.php');
class body_sql
{
    var $db;

    function __construct() {
        $this->db = new DBCon();
    }
    
    function body_sql()
    {
        self::__construct();
    }

    function get_bodies()
    {

        $sql = "select * from bodies ";
        $sql .= " order by body";
        //echo "<br>SQL:" . $sql;
        
        $result = $this->db->fetch_from_db($sql);
        return $result;			
    }

    function get_herb_bodies($herbID)
    {

        $sql = "SELECT bodies.body, bodies.definition, bodies.body_index ";
        $sql .= "FROM bodies "; 
        $sql .= "INNER JOIN herb_bodies ";
        $sql .= "ON bodies.body_index = herb_bodies.body_index ";
        $sql .= "where herb_bodies.herb_index = " . $herbID;
        $sql .= " order by bodies.body ";

        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;

    }	

}//end clas
