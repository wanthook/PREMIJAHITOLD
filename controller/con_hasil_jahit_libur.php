<?php
/*
 * create by Taufiq Hari widodo
 */
include '../inc/session.php';
include '../inc/gabungan.php';

//include 'json_head.php';

$user = new user();
$conect = new connection();
$global = new globalfunc();

$con = $conect->openDb();

$action = $global->get_param("action");

$jahit = new hasil_jahit_libur();

$jahit->start_rows = intval($global->get_param("jtStartIndex"));
$jahit->size_rows = intval($global->get_param("jtPageSize"));

$jahit->src['typ'] = $global->get_param("src");

$result = array();



switch($action)
{
    case "view":  
		$dt = $global->get_param("SdatePeriode");
        $jj = $global->get_param("SjenisJahit");
        $jk = $global->get_param("SjamKerja");
        $hg = $global->get_param("SHanging");
        
        if(!empty($jj))
        {
            $jahit->additionalQuery = " and a.jenis_id = '".$jj."'";
        }
        if(!empty($jk))
        {
            $jahit->additionalQuery .= " and a.transaksi_hasil_jahit_jam_kerja = '".$jk."' ";
        }
        if(!empty($hg))
        {
            $jahit->additionalQuery .= " and a.hanging_id = '".$hg."' ";
        }
        if(!empty($dt))
        {
            $jahit->additionalQuery .= " and a.transaksi_hasil_jahit_date between ('$dt-22' - interval 1 month) and '$dt-21' ";
        }
        $idx = $jahit->get_items();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalData();
	$result['Records'] = $idx;        
    break;
    case "viewDet": 
        $jahit->master['HasilJahitId'] = $global->get_param("HasilJahitId");
        $idx = $jahit->get_items_det();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalData();
	$result['Records'] = $idx;        
    break;
    case "viewUkuranDet": 
        $jahit->master['HasilJahitDetId'] = $global->get_param("HasilJahitDetId");
        $idx = $jahit->get_items_ukuran_det();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalDataUkuranDet();
	$result['Records'] = $idx;        
    break;
    case "viewKaryawan": 
        $kar = new karyawan();
        $kar->master['HasilJahitId'] = $global->get_param("HasilJahitId");
        $kar->master['HangingId'] = $global->get_param("HangingId");
        
        $idx = $kar->get_items_search();
        $result['Result'] = "OK";
//        $result['TotalRecordCount'] = $jahit->getTotalDataUkuranDet();
	$result['Records'] = $idx;        
    break;
    case "create":
        $jahit->master['HasilJahitId'] = $global->get_param('HasilJahitId');
        $jahit->master['HasilJahitDate'] = $global->get_param('HasilJahitDate');   
        $jahit->master['JenisId'] = $global->get_param('JenisId');   
        $jahit->master['IndexJamKerja'] = $global->get_param('IndexJamKerja'); 
        $jahit->master['HangingId'] = $global->get_param('HangingId');
        $sv = $jahit->save_items();
        if($sv['res']!="OK")
        {
            $result["Message"] = $sv['msg'];
        }
        else 
        {
            $idx = $jahit->get_items(true);
            $result['Record'] = $idx;
        }        
        $result['Result'] = $sv['res'];         
        
    break;
    case "createDet":
        $jahit->master['HasilJahit'] = $global->get_param('HasilJahitId');
        $jahit->master['karyawan_id'] = $global->get_param('KaryawanIdDet'); 
        $sv = $jahit->save_items_det();
        $idx = $jahit->get_items_det(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "createUkuranDet":
        $jahit->master['HasilUkuranDetId'] = $global->get_param('HasilUkuranDetId');
        $jahit->master['HasilJahitDetIdUkuran'] = $global->get_param('HasilJahitDetIdUkuran');   
        $jahit->master['DetailUkuranIdDetail'] = $global->get_param('DetailUkuranIdDetail');  
        $jahit->master['TimeId'] = $global->get_param('TimeId');   
        $jahit->master['DetailUkuranJumlahDetail'] = $global->get_param('DetailUkuranJumlahDetail');
        $sv = $jahit->save_ukuran_items_det();
        $idx = $jahit->get_items_ukuran_det(true);
        
        $jahit->hitung_premi($jahit->master['HasilJahitDetIdUkuran']);
        
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "update":
        $jahit->master['HasilJahitId'] = $global->get_param('HasilJahitId');
        $jahit->master['HasilJahitDate'] = $global->get_param('HasilJahitDate');   
        $jahit->master['JenisId'] = $global->get_param('JenisId');   
        $jahit->master['IndexJamKerja'] = $global->get_param('IndexJamKerja'); 
        $jahit->master['HangingId'] = $global->get_param('HangingId');
        
        $sv = $jahit->update_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "updateDet":
        $jahit->master['IndexDetId'] = $global->get_param('IndexDetId');
        $jahit->master['IndexIdDet'] = $global->get_param('IndexIdDet');   
        $jahit->master['SizeId'] = $global->get_param('SizeId');   
        $jahit->master['TargetMin'] = $global->get_param('TargetMin'); 
        $jahit->master['TargetFrom'] = $global->get_param('TargetFrom');
        $jahit->master['TargetUntil'] = $global->get_param('TargetUntil');
        $jahit->master['TargetMax'] = $global->get_param('TargetMax');
        
        $sv = $jahit->update_items_det();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "updateUkuranDet":
        $jahit->master['HasilUkuranDetId'] = $global->get_param('HasilUkuranDetId');
        $jahit->master['HasilJahitDetIdUkuran'] = $global->get_param('HasilJahitDetIdUkuran');   
        $jahit->master['DetailUkuranIdDetail'] = $global->get_param('DetailUkuranIdDetail');   
        $jahit->master['DetailUkuranJumlahDetail'] = $global->get_param('DetailUkuranJumlahDetail');
        $jahit->master['TimeId'] = $global->get_param('TimeId');   
        
        $sv = $jahit->update_items_ukuran_det();
        
        $jahit->hitung_premi($jahit->master['HasilJahitDetIdUkuran']);
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "delete":
        $jahit->master['IndexId'] = $global->get_param('IndexId');
        
        $sv = $jahit->delete_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "deleteDet":
       $jahit->master['HasilJahitDetId'] = $global->get_param('HasilJahitDetId');
        
        $sv = $jahit->delete_items_det();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "deleteUkuranDet":
       $jahit->master['HasilUkuranDetId'] = $global->get_param('HasilUkuranDetId');
        $jahit->master['HasilJahitDetIdUkuran'] = $global->get_param('HasilJahitDetId');
        $sv = $jahit->delete_items_ukuran_det();
        
        $jahit->hitung_premi($jahit->master['HasilJahitDetIdUkuran']);
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "jen":
        $hang = new jenis_jahit();
        $hang->src = 'act';
        $idx = $hang->get_items();;
        
        $result['Result'] = "OK";
        $arr = array();
        foreach ($idx as $key => $value)
        {
            $arr[] = array("DisplayText"=>$value['JenisDescription'],
                           "Value"=>$value['JenisId']);
        }
        $result['Options'] = $arr;
    break;
    case "jamker":
        $jK = $global->get_app_ref("APP", "JKR");
        
        for($i=0 ; $i<count($jK) ; $i++)
        {
            $jK[$i]["DisplayText"] = $jK[$i]["ref_description"];
            $jK[$i]["Value"] = $jK[$i]["ref_value"];
            
            unset($jK[$i]["ref_description"]);
            unset($jK[$i]["ref_value"]);
        }
        
        $result['Result'] = "OK";
        
        $result['Options'] = $jK;
    break;
    case "time":
        $jK = $global->get_app_ref("APP", "TMR");
        
        for($i=0 ; $i<count($jK) ; $i++)
        {
            $jK[$i]["DisplayText"] = $jK[$i]["ref_description"];
            $jK[$i]["Value"] = $jK[$i]["ref_value"];
            
            unset($jK[$i]["ref_description"]);
            unset($jK[$i]["ref_value"]);
        }
        
        $result['Result'] = "OK";
        
        $result['Options'] = $jK;
    break;
    case "hang":
        $hang = new hanging();
        $hang->src = 'act';
        $idx = $hang->get_items();;
        
        $result['Result'] = "OK";
        $arr = array();
        foreach ($idx as $key => $value)
        {
            $arr[] = array("DisplayText"=>$value['HangingDescription'],
                           "Value"=>$value['HangingId']);
        }
        $result['Options'] = $arr;
    break;
    case "siz":
        $hang = new ukuran();
        $hang->src = 'act';
        $idx = $hang->get_items();;
        
        $result['Result'] = "OK";
        $arr = array();
        foreach ($idx as $key => $value)
        {
            $arr[] = array("DisplayText"=>$value['UkuranDescription'],
                           "Value"=>$value['UkuranId']);
        }
        $result['Options'] = $arr;
    break;
    case "sizDet":
        $hang = new ukuran();
        $hang->src = 'act';
        $idx = $hang->get_items_det();
        
        $result['Result'] = "OK";
        $arr = array();
        foreach ($idx as $key => $value)
        {
            $arr[] = array("DisplayText"=>$value['UkuranDetailCode']." (".$value['UkuranDetailDescription'].")",
                           "Value"=>$value['ItemDetId']);
        }
        $result['Options'] = $arr;
    break;
}
print json_encode($result);
$conect->closeDb($con);
?>
