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
<script src="./js/app/reportweek.js"></script>
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
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<!-- Box -->
<div class="box">
    <!-- Table -->
    <div class="filtering">
        <form>
            <table>
				<div class="box-head">
					<h2>Report Rekap Mingguan</h2>
				</div>
                <tr>
                    <td width="150px">Tanggal</td>
                    <td><input type="text" name="txtDate" id="txtDate"/></td>
                </tr>
                <tr>
                    <td width="150px">Hanging</td>
                    <td>
                        <select name="cmbHanging" id="cmbHanging" class="field">
                            <option value="all">--SEMUA--</option>
                            <?
                            $sql = "select * from jht_master_hanging order by hanging_desc asc";
                            $res = mysql_query($sql);
                            while($row = mysql_fetch_assoc($res))
                            {
                                ?><option value="<?=$row['hanging_id']?>"><?=$row['hanging_desc']?></option><?
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150px">&nbsp;</td>
                    <td>
                        <button id="cmdReportWeek">Download</button>
                    </td>
                </tr>
            </table>
<!--            Search Invoice Number: <input type="text" name="txtInvoice" id="txtInvoice" />
            <button type="submit" id="LoadRecordsButton">Load records</button>-->
        </form>
    </div>
    <div id="reportWeek"></div>
    <!-- Table -->
</div>
<?
$conect->closeDb($con);
?>