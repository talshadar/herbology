<?php

##########################################################################
#  Herbology
#  Herb Data class
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.dbcon.php');
class herbs_sql
{

    var $db;

    public function __construct()
    {
        $this->db = new DBCon();
    }

    function herbs_sql()
    {
        self::__construct();
        //$this->db = new DBCon('localhost', 'herbadmn', 'passwd', 'herbology');
        //$this->db->connect;
    }

    function get_herbs($filter="", $order="")
    {
        ###########################
        #
        #  Have to rethink filtering/ordering - herb has mutliple properties, energetics and bodies
        #  change to filtering for one of the three might be the only way.
        #
        ##########################


        /*
        if ($filter != "")
        {
            $sql = "SELECT herbs.*  ";
            $sql .= "FROM herbs "; 
            $sql .= "INNER JOIN herb_properties ";
            $sql .= "ON herbs.herb_index = herb_properties.herb_index ";
            $sql .= " where herb_properties.properties_index =" . $filter;
        }

        if ($order != "")
        {
            if ($order == "energetics")
            {
                $sql = "SELECT herbs.*  ";
                $sql .= "FROM herbs, energetics "; 
                $sql .= "INNER JOIN herb_energetics ";
                $sql .= "ON herbs.herb_index = herb_energetics.herb_index ";
                $sql .= "where energetics.energetics_index = herb_energetics.energetics_index "
                $sql .= "order by energetics.term"

                $sql = "SELECT energetics.term, energetics.definition, energetics.energetics_index ";
                $sql .= "FROM energetics "; 
                $sql .= "INNER JOIN herb_energetics ";
                $sql .= "ON energetics.energetics_index = herb_energetics.energetics_index ";
                $sql .= "where herb_energetics.herb_index = " . $herbID;
                $sql .= " order by energetics.term ";
            }
            elseif ($order == "bodies")
            {	
                $sql = "SELECT bodies.body, bodies.definition, bodies.body_index ";
                $sql .= "FROM bodies "; 
                $sql .= "INNER JOIN herb_bodies ";
                $sql .= "ON bodies.body_index = herb_bodies.body_index ";
                $sql .= "where herb_bodies.herb_index = " . $herbID;
                $sql .= " order by bodies.body ";
            }

        }
        else
        {
            $sql .= " order by herb";			
        }
        */

        $sql = "SELECT herbs.* ";
        $sql .= "FROM herbs "; 
        $sql .= " order by herb";
        //$sql .= " limit 15";

        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;			
    }//end get_herbs

    function get_herb($herbID)
    {

        $sql = "select * from herbs where herb_index =" . $herbID;
        //echo "<br>SQL:" . $sql;

        $result = $this->db->fetch_from_db($sql);
        return $result;			
    }

    function get_herbImages($herbID)
    {

        $sql = "SELECT herb_images.image_filename, herb_images.image_description ";
        $sql .= "FROM herb_images "; 
        $sql .= "INNER JOIN herbs ";
        $sql .= "ON herb_images.herb_index = herbs.herb_index ";
        $sql .= "where herbs.herb_index = " . $herbID;

        //echo "<br>SQL:" . $sql;
        $result = $this->db->fetch_from_db($sql);
        return $result;				
    }

    function update_herb($data)
    {
        $result = "failed";
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        $id = $data["id"];
        //need to update herbs, herb_properties, herb_energetics, herb_images, herb_parts, herb_body
        $count = 1;
        $sql = '';

        foreach ($data as $field => $value)
        { 
            if ($field != "property" && $field != "energetics" && $field != "bodies" && $field != "ailments" && $field != "id")
            {
                $updateData[$count] = "`" . $field . "` = " . json_encode($value) ."";					
                $count += 1;				
            }
            elseif ($field == "property")
            {
                foreach ($value as $propCount => $propIndex)
                {
                    if ($propCount >=1) { $propertiesUpdate .= ', '; }
                    $propertiesUpdate .= '(' . $id . ',' . $propIndex . ')';
                    //insert into herb_properties(herb_index, properties_index) values ( 
                    //   (3,54),(3,56),(3,78) etc
                }
            }
            elseif ($field == "energetics")
            {
                foreach ($value as $enerCount => $enerIndex)
                {
                    if ($enerCount >=1) { $energeticsUpdate .= ', '; }
                    $energeticsUpdate .= '(' . $id . ',' . $enerIndex . ')';
                }

            }
            elseif ($field == "ailments")
            {
                foreach ($value as $ailmentCount => $ailmentIndex)
                {
                    if ($ailmentCount >=1) { $ailmentsUpdate .= ', '; }
                    $ailmentsUpdate .= '(' . $id . ',' . $ailmentIndex . ')';
                }

            }
            elseif ($field == "bodies")
            {
                foreach ($value as $bodyCount => $bodyIndex)
                {
                    if ($bodyCount >=1) { $bodiesUpdate .= ', '; }
                    $bodiesUpdate .= '(' . $id . ',' . $bodyIndex . ')';
                }
            }
        }

        //echo $propertiesUpdate;
        //echo $energeticsUpdate;
        //echo "bodies:" . $bodiesUpdate;

        $sql = 'update herbs set ' . implode(',',$updateData);
        $sql .= " where `herb_index` = " . $id;			
        //echo "<pre>";
        //print_r($updateData);
        //echo "<br>" . $sql;
        //echo "</pre>";
        $this->db->update_to_db($sql);


        //to update herb_properties etc - need to clear the existing records matching this herbID then write to the table the new data

        $sql = "delete from herb_properties where herb_index =" . $id;
        $this->db->delete_from_db($sql);

        $sql = "insert into herb_properties(herb_index, properties_index) values " . $propertiesUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

        $sql = "delete from herb_energetics where herb_index =" . $id;
        $this->db->delete_from_db($sql);

        $sql = "insert into herb_energetics(herb_index, energetics_index) values " . $energeticsUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

        $sql = "delete from herb_ailments where herb_index =" . $id;
        $this->db->delete_from_db($sql);

        $sql = "insert into herb_ailments(herb_index, ailment_index) values " . $ailmentsUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

        $sql = "delete from herb_bodies where herb_index =" . $id;
        $this->db->delete_from_db($sql);

        $sql = "insert into herb_bodies(herb_index, body_index) values " . $bodiesUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

         $result = "Succeeded";
         return $result;
    }//end update_herb

    function save_Herb($data)
    {
        //first get the last id
        $id = (int) $this->get_lastid("herb_index","herbs") +1;
//          echo "<br>ID" . $id;
        $fields[0] = "`herb_index`";
        $values[0] = $id ;
        $count = 1 ;
        foreach ($data as $field => $value)
        { 
            if ($field != "property" && $field != "energetics" && $field != "bodies" && $field != "ailments" && $field != "id")
            {
                $fields[$count] = "`" . $field . "`";
                $values[$count] = json_encode($value);
                $count += 1;				
            }
            elseif ($field == "property")
            {
                foreach ($value as $propCount => $propIndex)
                {
                    if ($propCount >=1) { $propertiesUpdate .= ', '; }
                    $propertiesUpdate .= '(' . $id . ',' . $propIndex . ')';
                    //insert into herb_properties(herb_index, properties_index) values ( 
                    //   (3,54),(3,56),(3,78) etc
                }
            }
            elseif ($field == "energetics")
            {
                foreach ($value as $enerCount => $enerIndex)
                {
                    if ($enerCount >=1) { $energeticsUpdate .= ', '; }
                    $energeticsUpdate .= '(' . $id . ',' . $enerIndex . ')';
                    //insert into herb_properties(herb_index, properties_index) values ( 
                    //   (3,54),(3,56),(3,78) etc
                }
            }
            elseif ($field == "ailments")
            {
                foreach ($value as $ailmentCount => $ailmentIndex)
                {
                    if ($ailmentCount >=1) { $ailmentsUpdate .= ', '; }
                    $ailmentsUpdate .= '(' . $id . ',' . $ailmentIndex . ')';
                    //insert into herb_properties(herb_index, properties_index) values ( 
                    //   (3,54),(3,56),(3,78) etc
                }
            }
            elseif ($field == "bodies")
            {
                foreach ($value as $bodyCount => $bodyIndex)
                {
                    if ($bodyCount >=1) { $bodiesUpdate .= ', '; }
                    $bodiesUpdate .= '(' . $id . ',' . $bodyIndex . ')';
                }
            }				
        }

        $sql = 'insert into herbs (' . implode(',',$fields) . ') values (' . implode(',',$values) . ')';

        //echo "<pre>";
        //print_r($data);
        //echo "<br>" . $sql;
        //echo "</pre>";

        //echo "<br>SQL: " . $sql;

        $this->db->insert_to_db($sql);

        $sql = "insert into herb_properties(herb_index, properties_index) values " . $propertiesUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

        $sql = "insert into herb_energetics(herb_index, energetics_index) values " . $energeticsUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);						 

        $sql = "insert into herb_ailments(herb_index, ailment_index) values " . $ailmentsUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);						 

        $sql = "insert into herb_bodies(herb_index, body_index) values " . $bodiesUpdate;
        //echo "<br>" . $sql;
        $this->db->insert_to_db($sql);

        $result = "Succeeded";
        return $result;

    }//end saveHerb

    function save_herb_images($data)
    {
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";
        $herbID = $data["herb"];
        $herbName = $data["herbName"];

        foreach ($data as $field => $value)
        { 
            if ($field == "files")
            {
               foreach ($value as $imageCount => $imageFileName)
                {
                    if ($imageCount >=1) { $filesUpdate .= ', '; }
                    $filesUpdate .= '(' . $herbID . ',"' . $imageFileName . '")';
                    //insert into herb_properties(herb_index, properties_index) values ( 
                    //   (3,54),(3,56),(3,78) etc
                }
            }
        }//end foreach Data

        $sql = "insert into herb_images(herb_index, image_filename) values " . $filesUpdate;
        //echo $sql;
        $this->db->insert_to_db($sql);

        $result = "Succeeded";
        return $result;

    }//end save_herb_images

    function get_lastid($field,$table)
    {
        $sql = "select max(" . $field . ") from " . $table;
//			echo $sql . "<br>";
        $result = $this->db->fetch_from_db($sql);
        return (int)$result[0][0];

    }		

		
/*

energetics and properties classes will pull properties and energetics via herbId
will do the reverse - pull herbs via properties and energetics ids for herb search

SELECT energetics.term 
FROM energetics 
INNER JOIN herb_energetics 
ON energetics.energetics_index = herb_energetics.energetics_index 
where herb_energetics.herb_index = 1 


SELECT properties.term 
FROM properties 
INNER JOIN herb_properties
ON properties.properties_index = herb_properties.properties_index 
where herb_properties.herb_index = 1 

*/		
		
}//end clas


