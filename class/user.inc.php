<?php

/*
 * create by Taufiq Hari widodo
 */
class user
{
    public $username;
    public $password;
            
    function login()
    {
        $sql = "select * from tp_user 
                where UserId = '".$this->username."' 
                and UserPassword = md5('".$this->password."')";
        $res = mysql_query($sql);       
        $num = mysql_num_rows($res);
        if($num)
        {
            $row = mysql_fetch_assoc($res);
                        
            $_SESSION['UserId'] = $row['UserId'];
            $_SESSION['UserName'] = $row['UserName'];
            $_SESSION['UserLevel'] = $row['UserLevel'];
            
//            $sIns = "update from tp_user UserId set LoginDate = now() where UserId = '".$_SESSION['UserId']."'";
            
            return true;
        }
        return false;
    }
}
?>
