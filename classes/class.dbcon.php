<?php
##########################################################################
#  Herbology
#  database class
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

  class DBCon {
    private $connection; // this is where the object will store the connection for other methods to access it

    public function __construct($host, $username, $password, $database)
    {
        $this->connection = mysqli_connect($host, $username, $password, $database);
        if ($host) 
	{
            $this->connection->select_db($database); 
            if (!$this->connection) 
            {
                die('An error occured while trying to connect to the database.');
            }
            return $this->connection;
        }
    }

    // this is so databaseConnection $db can access the connection for escaping MySQLi SQL
    public function connection(){
         return $this->connection;
    }

    function fetch_from_db($query) {
        //$query = mysqli_query($connection->$connection, 'QUERY');
        $result = $this->connection->query($query);
        $dataSet = Array();
				
        if ($this->connection->error) {
          print("Query failed: %s\n" . $this->connection->error);
          exit;
        }      
        while($row = $result->fetch_row()) {
          $dataSet[]=$row;
        }
        $result->close();
				
        return $dataSet;
    }
		
		function insert_to_db($query)
		{
        $result = $this->connection->query($query);
		}
		
		function update_to_db($query)
		{
        $result = $this->connection->query($query);
		}

		function delete_from_db($query)
		{
        $result = $this->connection->query($query);
		}
				
		

    // this is a magic function that is called when the object is destroyed, so we will close the connection to avoid to many connections
    function __destruct(){
      $this->connection()->close();
    }
		
		
}//end class


?>
