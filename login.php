<?php

/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */
session_start();
include './inc/gabungan.php';

$user = new user();
$conect = new connection();
$global = new globalfunc();
$con = $conect->openDb();
$error = null;

if(isset($_REQUEST['cmdLogin']))
{

    $user->username = $global->get_param("txtUsername");
    $user->password = $global->get_param("txtPassword");
    
    $log = $user->login();
    
    if($log)
        header ("Location:index.php");
    else
        $error = LOGIN_GAGAL;
    
}
$conect->closeDb($con);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?=TITLE?></title>
        <?=$global->include_style()?>
        <?=$global->include_js()?>
        <script>
            $(document).ready(function()
            {
                $("#cmdLogin").button();
            });
        </script>
    </head>
    <body>
        <div id="header">
            <div class="shell">
                    <div id="top">
                        <h1><img src="./images/ij.png"></img></h1>
                    </div>
            </div>
        </div>
        <div id="container">
            <div class="shell">
                <div class="box centertb" style="width: 350px;">
                    <div class="box-head"><h2>Login</h2></div>
                        <form method="post" name="frmLogin" enctype="multipart/form-data">
                            <div class="form">
                                <p class="inline-field">
                                    <label>Username:</label>
                                    <input type="text" class="field ui-widget-content ui-corner-all" name="txtUsername" id="txtUsername" style="width: 320px; height: 25px;"></input>
                                    <label>Password:</label>
                                    <input type="password"  class="field ui-widget-content ui-corner-all" name="txtPassword" id="txtPassword" style="width: 320px; height: 25px;"></input>
                                </p>                       
                            </div>
                            <div class="buttons">                                
                                <input type="submit" id="cmdLogin" name="cmdLogin" value="Login" style="width: 100px; height: 30px;" />
                            </div>
                        </form>
                </div>
                <?
                if(!empty($error))
                    $global->alert_ui($error);
                ?>
            </div>
        </div>
<!--        <div id="footer">
            <div class="shell">
                <span class="left">&copy; 2013 - Terry Palmer.</span>		
            </div>
        </div>-->
    </body>
</html>
