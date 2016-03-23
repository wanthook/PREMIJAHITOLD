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
<script src="./js/app/toolskalkulatorjahit.js"></script>

<!-- Box -->
<div class="box">
    <!-- Table -->
    <div class="filtering">
        <form id="kalkulator">
            <table>
                <div class="box-head">
                    <h2>Kalkulator Hasil Jahit</h2>
                </div>
                <tr>
                    <td>Jenis Jahit</td>
                    <td>
                        <select id="cmbJenisJahit" name="cmbJenisJahit">
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
                </tr>
                <tr>
                    <td>Jam Kerja</td>
                    <td>
                        <select name="cmbJamKerja" id="cmbJamKerja">
                            <?
                            $jK = $global->get_app_ref("APP", "JKR");
                            for($i=0 ; $i<count($jK) ; $i++)
                            {
                                ?><option value="<?=$jK[$i]['ref_value']?>"><?=$jK[$i]['ref_description']?></option><?
                            }
                            ?>
                        </select>                        
                    </td>
                </tr>
                <?
                $uk = new ukuran();
                $uk->src = "detail";
                $ukDet = $uk->get_items_det();
                
                foreach($ukDet as $k=>$v)
                {
                ?>
                <tr>
                    <td><?='('.$v['UkuranDetailCode'].') '.$v['UkuranDetailDescription']?></td>
                    <td>
                        <input type="text" id="txt<?=$v['ItemDetId']?>" name="txt[<?=$v['ItemDetId']?>]" style="width:100px;"/>
                    </td>
                </tr>
                <?
                }
                ?>
                <tr>
                    <td colspan="2"><button id="cmdCalc" style="width:100px;">Kalkulasi</button><hr></td>
                </tr>
                <tr>
                    <td>Persen</td>
                    <td>
                        <input type="text" id="persen" style="width:100px;" readonly />
                    </td>
                </tr>
                <tr>
                    <td>PCS</td>
                    <td>
                        <input type="text" id="pcs" style="width:100px;" readonly />
                    </td>
                </tr>
                <tr>
                    <td>PCS+Target Minimum</td>
                    <td>
                        <input type="text" id="targetpcs" style="width:100px;" readonly />
                    </td>
                </tr>
                <tr>
                    <td>Ukuran</td>
                    <td>
                        <input type="text" id="size" style="width:100px;" readonly />
                    </td>
                </tr>
                <tr>
                    <td>Premi</td>
                    <td>
                        <input type="text" id="duit" style="width:100px;" readonly />
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div id="reportWeek"></div>
    <!-- Table -->
</div>
<?
$conect->closeDb($con);
?>