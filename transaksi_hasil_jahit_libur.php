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

$periode = date("Y-m");
?>
<script src="./js/app/transaksihasiljahitlibur.js"></script>
<script type="text/javascript">
   MYLIBRARY.init(["<?=$_SESSION['UserLevel']?>"]);
   MYLIBRARY.helloWorld();
</script>
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
<style id='hideMonth'></style>
<!-- Box -->
<div class="box">
    <!-- Table -->
	<div class="filtering">
        <form id="frm">
            <table>
<!--                <tr>
                    <td>Report By:</td>
                    <td colspan="3">
                        <select name="cmbType" id="cmbType">
                            <option value="">-- Please Type Your Report Type --</option>
                            <option value="product">Product</option>
                            <option value="periode">Periode</option>
                            <option value="week">Week</option>
                        </select>
                    </td>
                </tr>-->
                <tr id="searchTransHasil">
                    <td>Periode Bulan* :</td>
                    <td><div id="monthOnly"><input type="text" id="txtDatePeriode" name="txtDatePeriode" value="<?=$periode?>"/></div></td>
                    <td>Jenis Jahit :</td>
                    <td>
                        <select id="cmbJenisJahit" name="cmbJenisJahit">
                            <option value="">-- Pilih Jenis Jahit --</option>
                        <?
                        $jj = new jenis_jahit();
                        $jj->src="act";
                        $dJj = $jj->get_items();
                        foreach ($dJj as $kJj => $vJj)
                        {
                            ?>
                            <option value="<?=$vJj["JenisId"]?>"><?=$vJj["JenisDescription"]?></option>
                            <?
                        }
                        ?>
                        </select>
                    </td>
					<td rowspan="3"><button id="cmdSearch" name="cmdSearch" style="height:20px" >Cari</button></td>
                </tr>
                <tr>
<!--                    <td>Jam Kerja :</td>
                    <td>
                        <select id="cmbJamKerja" name="cmbJamKerja">
                            <option value="">-- Pilih Jam Kerja --</option>
                            <?
                            $jK = $global->get_app_ref("APP", "JKR");
                            for($i=0 ; $i<count($jK) ; $i++)
                            {
                                ?><option value="<?=$jK[$i]['ref_value']?>"><?=$jK[$i]['ref_description']?></option><?
                            }
                            ?>
                            <option value="N">Normal</option>
                            <option value="P">Pendek</option>
                        </select>
                    </td>-->
                    <td>Hanging :</td>
                    <td>
                        <select id="cmbHanging" name="cmbHanging">
                            <option value="">-- Pilih Jenis Hanging --</option>
                        <?
                        $hng = new hanging();
                        $hng->src="act";
                        $dHng = $hng->get_items();
                        foreach ($dHng as $kHng => $vHng)
                        {
                            ?>
                            <option value="<?=$vHng["HangingId"]?>"><?=$vHng["HangingDescription"]?></option>
                            <?
                        }
                        ?>
                        </select>
                    </td>                    
                </tr>
<!--                <tr id="date2">
                    <td>Week :</td>
                    <td><input type="text" id="txtWeek" name="txtWeek" /></td>
                </tr>-->
                <tr>
                    <td colspan="4"><i>* Periode dihitung dari tanggal 22 bulan sebelum s/d tanggal 21 bulan terpilih</i></td>
                </tr>
            </table>
        </form>
    </div>
    <div id="itemTableHasilJahitLibur"></div>
    <div id="itemsTableSearchKaryawanLibur"></div>
    <!-- Table -->
</div>
<?
$conect->closeDb($con);
?>