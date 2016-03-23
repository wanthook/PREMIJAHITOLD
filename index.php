<?php

/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */
include './inc/session.php';
include './inc/gabungan.php';

$user = new user();
$conect = new connection();
$global = new globalfunc();

$con = $conect->openDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?=TITLE?></title>
        <?=$global->include_style()?>
        <?=$global->include_js()?>
        <script src="js/terryidx.js"></script>
    </head>
    <body>
        <div id="header">
            <div class="shell">
                <div id="top">
                    <h1><img src="./images/ij.png"></img>&nbsp;</h1>
                    <div id="top-navigation">
						<div id="asdf" style="float:right">
                        <?=SELAMAT_DATANG?> <a href="#"><strong><?=$_SESSION['UserName']?></strong></a>
                        <span>|</span>
                        <a href="home.php" class="menuUtama">Home</a>
                        <span>|</span>
                        <a href="logout" class="lgout">Log out</a>
						</div>
						<br>
						<h1><?=TITLE?></h1>
						<!--<span>|</span>
                        <a href="dukungan/ChromeStandaloneSetup.exe" class="dukungan">Chrome</a>
						</br>
                        IP Anda : <?=$_SERVER['REMOTE_ADDR']?>-->
                    </div>
                </div>
            </div>
        </div>
        <div id="container">
            <div class="shell">
                <div id="main">
                    <div id="content">	
                        
                    </div>
                    <div id="sidebar">
                        
                        <div class="box"  id="menuBox">

                            <div class="box-head">
                                    <h2>Menu</h2>
                            </div>

                            <div class="box-content">                                            
                                <div id="accordion">
                                    <?=$global->create_menu_html()?>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="cl">&nbsp;</div>	
                </div>
            </div>
        </div>
<!--        <div id="footer">
            <div class="shell">
                <span class="left">&copy; 2013 - Indah Jaya, PT.</span>		
            </div>
        </div>-->
    </body>
</html>
<?php
$conect->closeDb($con);
?>