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

$jahit = new index_jahit_libur();

$jahit->start_rows = intval($global->get_param("jtStartIndex"));
$jahit->size_rows = intval($global->get_param("jtPageSize"));

$jahit->src['typ'] = $global->get_param("src");

$result = array();



switch($action)
{
    case "view":        
        $idx = $jahit->get_items();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalData();
	$result['Records'] = $idx;        
    break;
    case "viewDet": 
        $jahit->master['IndexIdDet'] = $global->get_param("IndexIdDet");
        $idx = $jahit->get_items_det();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalData();
	$result['Records'] = $idx;        
    break;
    case "create":
        $jahit->master['IndexId'] = $global->get_param('IndexId');
        $jahit->master['IndexTarget'] = strtoupper($global->get_param('IndexTarget'));   
        $jahit->master['JenisId'] = $global->get_param('JenisId');   
        $jahit->master['IndexJamKerja'] = $global->get_param('IndexJamKerja'); 
        $jahit->master['IndexIncentif'] = $global->get_param('IndexIncentif');
        $sv = $jahit->save_items();
        $idx = $jahit->get_items(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "createDet":
        $jahit->master['IndexDetId'] = $global->get_param('IndexDetId');
        $jahit->master['IndexIdDet'] = $global->get_param('IndexIdDet');   
        $jahit->master['SizeId'] = $global->get_param('SizeId');   
        $jahit->master['TargetMin'] = $global->get_param('TargetMin'); 
        $jahit->master['TargetFrom'] = $global->get_param('TargetFrom');
        $jahit->master['TargetUntil'] = $global->get_param('TargetUntil');
        $jahit->master['TargetMax'] = $global->get_param('TargetMax');
        $jahit->master['Premi'] = $global->get_param('Premi');
        $sv = $jahit->save_items_det();
        $idx = $jahit->get_items_det(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "update":
        $jahit->master['IndexId'] = $global->get_param('IndexId');
        $jahit->master['IndexTarget'] = strtoupper($global->get_param('IndexTarget'));   
        $jahit->master['JenisId'] = $global->get_param('JenisId');   
        $jahit->master['IndexJamKerja'] = $global->get_param('IndexJamKerja'); 
        $jahit->master['IndexIncentif'] = $global->get_param('IndexIncentif');
        
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
        $jahit->master['Premi'] = $global->get_param('Premi');
        
        $sv = $jahit->update_items_det();
        
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
       $jahit->master['IndexDetId'] = $global->get_param('IndexDetId');
        
        $sv = $jahit->delete_items_det();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
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
    case "opt":
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
}
print json_encode($result);
$conect->closeDb($con);
?>
