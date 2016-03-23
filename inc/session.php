<?php

/*
 * create by Taufiq Hari widodo
 */
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
if(!isset($_SESSION['UserId']))
{
//    echo "jhsdjfhsdjkf";
    header("Location:login.php");
    exit();
}
?>
