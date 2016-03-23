<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of connection
 *
 * @author taufiq
 */
class connection {
    
    private $username;
    private $server;
    private $password;
    private $database;
    
    private $type;
            
    function __construct($type="MYSQL") 
    {
        $this->type = $type;
        $this->connection();
    }
    
    function connection()
    {
        $con = null;
        
        $fileIni = parse_ini_file("wantsetY79JJk.ini",true);
//        print_r($fileIni);
        switch ($this->type)
        {
            case "MYSQL":
                $this->username = $fileIni[$this->type]["username"];
                $this->password = $fileIni[$this->type]["password"];
                $this->server = $fileIni[$this->type]["server"];
                $this->database = $fileIni[$this->type]["database"];
                
//                $con = mysql_connect($this->server, $this->username, $this->password);
            break;
        
            case "MSSQL":
                $this->username = $fileIni[$this->type]["username"];
                $this->password = $fileIni[$this->type]["password"];
                $this->server = $fileIni[$this->type]["server"];
                $this->database = $fileIni[$this->type]["database"];
                
//                $con = mssql_connect($this->server, $this->username, $this->password);
            break;
        }
        
//        return $con;
    }
    
    function openDb()
    {
        $ret = null;
        
        $this->connection();
        
        switch ($this->type)
        {
            case "MYSQL":
                $con = mysql_connect($this->server, $this->username, $this->password);
                if(!$con)
                {
                    exit(showError('Error Connection To MySQL',__FILE__,__LINE__));
                }
                $ret = mysql_select_db($this->database, $con);
            break;
            case "MSSQL":
                $con = mssql_connect($this->server, $this->username, $this->password);
                $ret = mssql_select_db($this->database, $con);
            break;
        }
        
        return $ret;
    }
    
    function closeDb($source)
    {
        $ret = false;
        switch ($this->type)
        {
            case "MYSQL":
                $ret = @mysql_close($source);
            break;
            case "MSSQL":
                $ret = @mssql_close($sorce);
            break;
        }
        return $ret;
    }
}

?>
