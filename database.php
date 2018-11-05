<?php 
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
//define(DB_NAME, 'logger');
define('DB_NAME', 'login1');
class database {
    
    //error_reporting(E_ALL);
        
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('config.ini'); 
            self::$connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }
    
    private function connectPDO() {    
        error_reporting(1);
        ini_set('display_errors', 1);
        // Try and connect to the database
        try {
            
           
           
           # MySQL with PDO_MYSQL
            $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            // set the PDO error mode to exception
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
            # MySQL with PDO_MYSQL
           // return $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);

          }
          catch(PDOException $e) {
              echo $e->getMessage();
          }
    }
    
    protected function connectPDO1() {    
        error_reporting(1);
        ini_set('display_errors', 1);
        // Try and connect to the database
        try {
            # MySQL with PDO_MYSQL
            $this->dbh = $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            // set the PDO error mode to exception
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->dbh;
           //$config = parse_ini_file('config.ini'); 
          // $dbname = $config['dbname'];
            # MySQL with PDO_MYSQL
            //return $this->dbh = $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);

          }
          catch(PDOException $e) {
              echo $e->getMessage();
          }
    }
    
    private function queryPDOFetchRow($query, $data) {
        // Connect to the database
        $dbh = $this -> connectPDO();
        
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetch();
        
        // Query the database
        //$result = $connection -> queryPDO($query);

        return $result;
    }
    
    private function queryPDOFetchAll($query, $data) {
        // Connect to the database
        
        $dbh = $this -> connectPDO();
        $sth = $dbh->prepare($query);
        
        //$sth->execute($data);
        try {
            $res = $sth->execute($data);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            exit;
        }
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
       
        // Query the database
        //$result = $connection -> queryPDO($query);

        return $result;
    }
    
    private function queryInsertPDO($sql, $data)
    {
        $dbh = $this -> connectPDO();
        $values = array();
        $sth = $dbh->prepare($sql);
        foreach($data as $key => $value){
            $values[':'.$key] = $value;
        }
        $res = $sth->execute($values);
        
        if($res)
        {
            return $lastInsertID = $dbh->lastInsertId();
        }
        else
        {
            return 0;
        }
    }
    
    private function queryInsertPDO1($dbh, $sql, $data)
    {
        error_reporting(1);
        ini_set('display_errors', 1);
        $this->dbh = $dbh;
        $values = array();
        
        try {
            
            $sth = $this->dbh->prepare($sql);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        foreach($data as $key => $value){
            $values[':'.$key] = $value;
        }
        
        try {
            $res = $sth->execute($values);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        
        if($res)
        {
            $lastInsertID = $this->dbh->lastInsertId();
            $this->dbh = null;
            return $lastInsertID;
        }
        else
        {
            $this->dbh = null;
            return 0;
        }
    }
    
    private function queryInsertPDO2($dbh, $sql, $data)
    {
        $this->dbh = $dbh;
        $values = array();
        $sth = $this->dbh->prepare($sql);
        foreach($data as $key => $value){
            $values[':'.$key] = $value;
        }
        $res = $sth->execute($values);
        
        if($res)
        {
            $lastInsertID = $this->dbh->lastInsertId();
            $this->dbh = null;
            return $lastInsertID;
        }
        else
        {
            $this->dbh = null;
            return 0;
        }
    }
    
    public function queryUpdatePDO($sql, $data, $conditions = null)
    {
        $dbh = $this -> connectPDO();
        $values = array();
        $sth = $dbh->prepare($sql);
        if(isset($data) && count($data) > 0)
        {
            foreach($data as $key => $value){
                $values[':'.$key] = $value;
            }
        }
        
        if(isset($conditions) && count($conditions) > 0)
        {
            
            foreach($conditions as $key => $value){
                if(!is_array($value))
                {
                    $values[':'.$key] = $value;
                }
                else
                {
                    foreach($value as $key1 => $value1){
           
                        $values[':'.$key1] = $value1['value'];
                    }
                }
            }
        }
      
        try {
            $res = $sth->execute($values);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
       
        if($res)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public function queryUpdatePDO1($sql, $data, $conditions = null)
    {
        $values = array();
        $sth = $this->dbh->prepare($sql);
        if(isset($data) && count($data) > 0)
        {
            foreach($data as $key => $value){
                $values[':'.$key] = $value;
            }
        }
        
        if(isset($conditions) && count($conditions) > 0)
        {
            foreach($conditions as $key => $value){
                $values[':'.$key] = $value;
            }
        }
        try {
            $res = $sth->execute($values);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        $this->dbh = null;
        if($res)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public function queryUpdatePDO2($dbh, $sql, $data, $conditions = null)
    {
        $this->dbh = $dbh;
        $values = array();
        $sth = $this->dbh->prepare($sql);
        if(isset($data) && count($data) > 0)
        {
            foreach($data as $key => $value){
                $values[':'.$key] = $value;
            }
        }
        
        if(isset($conditions) && count($conditions) > 0)
        {
            foreach($conditions as $key => $value){
                $values[':'.$key] = $value;
            }
        }
        try {
            $res = $sth->execute($values);
        }
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        //$this->dbh = null;
        if($res)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query) {
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }
    

    public function fetchRow($table_name,$where = null, $orderValue = null, $orderBy = null, $limit = null,$noOfRecords = null, $orderAs = null)
    {
        $html = '';
        $values = array();
        if($where != null)
        {
            $count = 1;
            foreach($where AS $key => $value)
            {
                if(!is_array($value))
                {
                    $values[] = $value;
                    $html.=" AND ";
                    $html.= $key.'=?';
                }
                else
                {
                    foreach($value AS $key1 => $value1)
                    {
                        $values[] = $value1['value'];
                        $html.=" AND ";
                        $html.= '`'.$key1 . '`' . $value1['operator'] . '?';
                    }
                }
                $count++;
            }
        }
        
        
        $htmlWhere = ' WHERE deleted_at IS NULL ';
        $htmlWhere.= $html;
        
        $orderHtml = '';
        if($orderValue != null)
        {
            if($orderAs != null)
            {
                $orderHtml.= " ORDER BY CAST(`".$orderValue."` AS ".$orderAs.") ";
            }
            else
            {
                $orderHtml.= " ORDER BY `".$orderValue."` ";
            }
            
            
            if($orderBy != null)
            {
               $orderHtml.= " ".$orderBy; 
            }
        }
        
        $limitHtml = '';
        if($noOfRecords != null)
        {
            if($limit == null)
            {
                $limit = 0;
            }
            $limitHtml.= " LIMIT ".$limit.",".$noOfRecords." ";
            
        }
        $sql = "SELECT * FROM ".$table_name." ".$htmlWhere." ".$orderHtml." ".$limitHtml;
        
        return $this->queryPDOFetchRow($sql, $values);
        
    }

    protected function fetchAll($table_name,$data = null, $orderValue = null, $orderBy = null, $limit = null,$noOfRecords = null, $orderAs = null)
    {
        $html = '';
        $values = array();
        if($data != null)
        {
            foreach($data AS $key => $value)
            {
                if(!is_array($value))
                {
                    $values[] = $value;
                    $html.=" AND ";
                    $html.= '`'.$key.'`=?';
                }
                else
                {
                    foreach($value AS $key1 => $value1)
                    {
                        $values[] = $value1['value'];
                        $html.=" AND ";
                        $html.= '`'.$key1 . '`' . $value1['operator'] . '?';
                    }
                }
            }
        }
        $htmlWhere = " WHERE deleted_at IS NULL ";
        
        if($data != null)
        {
            $htmlWhere.= $html;
        }
        
        $orderHtml = '';
        if($orderValue != null)
        {
            if($orderAs != null)
            {
                $orderHtml.= " ORDER BY CAST(`".$orderValue."` AS ".$orderAs.") ";
            }
            else
            {
                $orderHtml.= " ORDER BY `".$orderValue."` ";
            }
            
            if($orderBy != null)
            {
               $orderHtml.= " ".$orderBy; 
            }
        }
        
        $limitHtml = '';
        if($noOfRecords != null)
        {
            if($limit == null)
            {
                $limit = 0;
            }
            $limitHtml.= " LIMIT ".$limit.",".$noOfRecords." ";
            
        }
        $sql = "SELECT * FROM ".$table_name." ".$htmlWhere." ".$orderHtml." ".$limitHtml;
       
       return $this->queryPDOFetchAll($sql, $values);
    }
    
    protected function insert( $table_name,$data)
    {
        $data['created_at'] = $data['updated_at'] = date("Y-m-d H:i:s");
        $fields = array_keys($data);
        
       // $columnString = implode(',', array_flip($data));
        $valueString = ":".implode(',:', ($data));
        $valueString = '';
        $dataCount = count($data);
        $count = 0;
        foreach($data AS $key => $value)
        {
            if($count > 0 && $count != $dataCount)
            {
                $valueString.= ',';
            }
            $valueString.= ':'.$key;
            $count++;
        }
        
        // build the query
         $sql = "INSERT INTO ".$table_name."(`".implode('`,`', $fields)."`) VALUES(".$valueString.")";
     
        // run and return the query result resource
        return $this->queryInsertPDO( $sql, $data);
    }
    
    public function update($table_name,$data, $conditions=null)
    {
        $data['updated_at'] = date("Y-m-d H:i:s");
        $fields = array_keys($data);

        $valueString = '';
        $dataCount = count($data);
        $count = 0;
        foreach($data AS $key => $value)
        {
            if($count > 0 && $count != $dataCount)
            {
                $valueString.= ',';
            }
            $valueString.= '`'.$key.'`=:'.$key;
            $count++;
        }

        $conditionString = '';
        $dataCount = count($conditions);
        $count = 0;
        
        if($conditions != null)
        {
            foreach($conditions AS $key => $value)
            {
                if(!is_array($value))
                {
                    if($count > 0 && $count != $dataCount)
                    {
                        $conditionString.= ' AND ';
                    }
                    else
                    {
                        $conditionString.= ' WHERE ';
                    }
                    $conditionString.= '`'.$key.'`=:'.$key;
                    $count++;
                }
                else
                {
                    foreach($value AS $key1 => $value1)
                    {
                        if($count > 0)
                        {
                            $conditionString.= ' AND ';
                        }
                        else
                        {
                            $conditionString.= ' WHERE ';
                        }
                        
                        $conditionString.= '`'.$key1.'`=:'.$key1;
                        $count++;
                    }
                }
                
            }
        }

        // build the query
         $sql = "UPDATE ".$table_name." SET ".$valueString . $conditionString;
       // run and return the query result resource
         $res = $this->queryUpdatePDO($sql, $data, $conditions);
         
         return $res;
    }
    
    public function callQuery($query, $values = null)
    {
       return $this->queryPDOFetchAll($query, $values); 
    }
    
    protected function insert1($dbh, $table_name, $data)
    {
        $data['created_at'] = $data['updated_at'] = date("Y-m-d H:i:s");
        // retrieve the keys of the array (column titles)

        $fields = array_keys($data);
        
       // $columnString = implode(',', array_flip($data));
        $valueString = ":".implode(',:', ($data));
        $valueString = '';
        $dataCount = count($data);
        $count = 0;
        foreach($data AS $key => $value)
        {
            if($count > 0 && $count != $dataCount)
            {
                $valueString.= ',';
            }
            $valueString.= ':'.$key;
            $count++;
        }
        
        // build the query
       $sql = "INSERT INTO ".$table_name."(`".implode('`,`', $fields)."`) VALUES(".$valueString.")";
      
        // run and return the query result resource
        return $this->queryInsertPDO1($dbh, $sql,  $data);
    }
    
    protected function insert2($dbh, $table_name, $data)
    {
        $data['created_at'] = $data['updated_at'] = date("Y-m-d H:i:s");
        // retrieve the keys of the array (column titles)

        $fields = array_keys($data);
        
       // $columnString = implode(',', array_flip($data));
        $valueString = ":".implode(',:', ($data));
        $valueString = '';
        $dataCount = count($data);
        $count = 0;
        foreach($data AS $key => $value)
        {
            if($count > 0 && $count != $dataCount)
            {
                $valueString.= ',';
            }
            $valueString.= ':'.$key;
            $count++;
        }
        
        // build the query
       $sql = "INSERT INTO ".$table_name."(`".implode('`,`', $fields)."`) VALUES(".$valueString.")";
      
        // run and return the query result resource
        return $this->queryInsertPDO2($dbh, $sql,  $data);
    }
    
    public function update1($table_name,$data, $conditions=null)
    {
        $data['updated_at'] = date("Y-m-d H:i:s");
        // retrieve the keys of the array (column titles)

        $fields = array_keys($data);

       // $columnString = implode(',', array_flip($data));

        $valueString = '';
        $dataCount = count($data);
        $count = 0;
        foreach($data AS $key => $value)
        {
            if($count > 0 && $count != $dataCount)
            {
                $valueString.= ',';
            }
            $valueString.= '`'.$key.'`=:'.$key;
            $count++;
        }

        $conditionString = '';
        $dataCount = count($conditions);
        $count = 0;
        
        if($conditions != null)
        {
            foreach($conditions AS $key => $value)
            {
                if($count > 0 && $count != $dataCount)
                {
                    $conditionString.= ' AND ';
                }
                else
                {
                    $conditionString.= ' WHERE ';
                }
                $conditionString.= '`'.$key.'`=:'.$key;
                $count++;
            }
        }


        // build the query
        $sql = "UPDATE ".$table_name." SET ".$valueString . $conditionString;
   
        // run and return the query result resource
         $res = $this->queryUpdatePDO1($sql, $data, $conditions);
         
         return $res;
    }
    
    public function update2($dbh, $table_name,$data, $conditions=null)
    {
        $data['updated_at'] = date("Y-m-d H:i:s");
        // retrieve the keys of the array (column titles)

        $fields = array_keys($data);

       // $columnString = implode(',', array_flip($data));

        $valueString = '';
        $dataCount = count($data);
        $count = 0;
        foreach($data AS $key => $value)
        {
            if($count > 0 && $count != $dataCount)
            {
                $valueString.= ',';
            }
            $valueString.= '`'.$key.'`=:'.$key;
            $count++;
        }

        $conditionString = '';
        $dataCount = count($conditions);
        $count = 0;
        
        if($conditions != null)
        {
            foreach($conditions AS $key => $value)
            {
                if($count > 0 && $count != $dataCount)
                {
                    $conditionString.= ' AND ';
                }
                else
                {
                    $conditionString.= ' WHERE ';
                }
                $conditionString.= '`'.$key.'`=:'.$key;
                $count++;
            }
        }


        // build the query
        $sql = "UPDATE ".$table_name." SET ".$valueString . $conditionString;
   
        // run and return the query result resource
         $res = $this->queryUpdatePDO2($dbh, $sql, $data, $conditions);
         
         return $res;
    }
}
