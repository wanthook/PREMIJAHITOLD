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
$conect = new connection();
$global = new globalfunc();

$con = $conect->openDb();
?>
<script src="./js/app/masterjenisjahit.js"></script>
<style>
    .jtable-dialog-form
    {
        min-width: 400px;
    }

    .jtable-dialog-form input[type="text"]
    {
        min-width: 250px;
        min-height: 25px;
    }
</style>
<!-- Box -->
<div class="box">
    <!-- Table -->
<!--    <div class="filtering">
        <form>
            Search Design Code: <input type="text" name="txtDesign" id="txtDesign" />
            <button type="submit" id="LoadRecordsButton">Load records</button>
        </form>
    </div>-->
    <div id="itemTableJenisJahit"></div>
    <!-- Table -->
</div>
<?
$conect->closeDb($con);
?>