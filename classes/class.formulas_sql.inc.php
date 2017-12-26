<?php

##########################################################################
#  Herbology 
#  Formulas class
#  Formula as well as all components related to (parts, actions etc)
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.dbcon.php');
class formulas_sql
{
    var $db;

/*
formulas
 - formula_index
 - name
 - description

formula_herbs
 - formula_index
 - herb_index
 - part_amount
 - action_index

formula_preparations
 - formula_index
 - preparation_index

actions
 - action_index
 - action  (key, support, balance, catalyst)
 - description

preparations
 - preparation_index
 - type      ( tincture, infusion, capsule...) 
 - description
 */		
    public function __construct()
    {
        $this->db = new DBCon();
    }

    function formulas_sql()
    {
        self::__construct();
        //$this->db = new DBCon('localhost', 'herbadmn', 'passwd', 'herbology');
//          $this->db->connect;
    }


    function get_formulas()
    {

        $sql = "select * from formulas ";
        $sql .= " order by name ";
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;			
    }

    function get_formula($formulaID)
    {
        $sql = "select * from formulas ";
        $sql .= "where formula_index = " . $formulaID;
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;

    }

    function get_formula_preparations($formulaID)
    {
        $sql = "select preparations.type, preparations.description from preparations, formula_preparations ";
        $sql .= "where preparations.preparation_index = formula_preparations.preparation_index and formula_preparations.formula_index = " . $formulaID;
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;		
    }

    function get_formula_herbs($formulaID)
    {
        $sql = "select herbs.herb_index, herbs.herb, fh.part_amount, actions.action from herbs, formula_herbs fh, actions ";
        $sql .= " where ";
        $sql .= "herbs.herb_index = fh.herb_index ";
        $sql .= "and actions.action_index = fh.action_index ";
        $sql .= "and fh.formula_index = " . $formulaID;
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;			  
    }

    function get_herb_action($actionID)
    {

    }

    function get_actions()
    {
        $sql = "select * from actions ";
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;

    }

    function get_preparations()
    {
        $sql = "select * from preparations order by type ";
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;		
    }

    function get_herb_formulas($herbID)
    {


        $sql = "SELECT formulas.name, formulas.definition, formulas.formula_index ";
        $sql .= "FROM formulas "; 
        $sql .= "INNER JOIN herb_formulas ";
        $sql .= "ON formulas.formula_index = herb_formulas.formula_index ";
        $sql .= "where herb_formulas.herb_index = " . $herbID;
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;

    }

    function save_formula($data)
    {

        //first get the last id
        $id = (int) $this->get_lastid("formula_index","formulas") +1;
        //echo "<br>ID" . $id;
        $fields[0] = "`formula_index`";
        $values[0] = $id ;
        $count = 1 ;

        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";			


        foreach ($data as $field => $value)
        { 
            if ($field != "formulaHerbs" && $field != "preparations" && $field != "id")
            {
                $fields[$count] = "`" . $field . "`";
                $values[$count] = json_encode($value);
                $count += 1;				
            }
            elseif ($field == "formulaHerbs")
            {
                foreach ($value as $herbCount => $herbData)
                {
                    //echo "<pre>";
                    //print_r($herbData);
                    //echo "</pre>";						   
                    if ($herbCount >=1) { $herbsUpdate .= ', '; }
                    
                    $herbsUpdate .= '(' . $id . ',' . $herbData["herbID"] . ',' . $herbData["partAmount"] . ',' . $herbData["actionID"] . ')';
                         //insert into herb_properties(formula_index, herb_index, partAmount, action_index) values ( 
                         //   (3,54),(3,56),(3,78) etc
                }
            }
            elseif ($field == "preparations")
            {
                foreach ($value as $prepCount => $preparationIndex)
                {
                    if ($prepCount >=1) { $preparationsUpdate .= ', '; }
                    $preparationsUpdate .= '(' . $id . ',' . $preparationIndex . ')';
                    //insert into herb_properties(formula_index, preparation_index) values ( 
                    //   (3,54),(3,56),(3,78) etc
                }
            }
        }

        $sql = 'insert into formulas (' . implode(',',$fields) . ') values (' . implode(',',$values) . ')';

        //echo "<pre>";
        //print_r($data);
        //echo "<br>" . $sql;
        //echo "</pre>";

        //echo "<br>SQL: " . $sql;

        $this->db->insert_to_db($sql);

        $sql = "insert into formula_herbs(formula_index, herb_index, part_amount, action_index) values " . $herbsUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

        $sql = "insert into formula_preparations(formula_index, preparation_index) values " . $preparationsUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);						 


        $result = "Succeeded";
        return $result;		

    }//end save_formula



    function get_lastid($field,$table)
    {
        $sql = "select max(" . $field . ") from " . $table;
        //echo $sql . "<br>";
        $result = $this->db->fetch_from_db($sql);
        return (int)$result[0][0];

    }		

/*
SELECT properties.term 
FROM properties 
INNER JOIN herb_properties
ON properties.properties_index = herb_properties.properties_index 
where herb_properties.herb_index = 1 */
            
}//end clas
	



