<?
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
//$conect = new connection();
$global = new globalfunc();

//$con = $conect->openDb();
?>
<!-- Box -->
<div class="box">
    <!-- Table -->
    <div class="filtering">
			<div class="box-head">
				<h2>Home</h2>
			</div>
			<div class="container">
				<div id="content">
					<img src="./images/home_bg.png" style="padding: 3px;"></img>
				</div>
			</div>
    </div>
    <!-- Table -->
</div>
<?
//$conect->closeDb($con);
?>