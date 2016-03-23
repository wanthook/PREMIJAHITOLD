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

$action = $global->get_param("action");

$jahit = new ukuran();

$jahit->start_rows = intval($global->get_param("jtStartIndex"));
$jahit->size_rows = intval($global->get_param("jtPageSize"));

$jahit->src['typ'] = $global->get_param("src");

$result = array();

$con = $conect->openDb();

switch($action)
{
    case "view":        
        $idx = $jahit->get_items();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalData();
	$result['Records'] = $idx;        
    break;
    case "viewDet":        
        $jahit->master['UkuranIdDet'] = $global->get_param("UkuranIdDet");
        $idx = $jahit->get_items_det();
        $result['Result'] = "OK";
        $result['TotalRecordCount'] = $jahit->getTotalDataDet();
	$result['Records'] = $idx;        
    break;
    case "create":
        $jahit->master['UkuranId'] = $global->get_param('UkuranId');
        $jahit->master['UkuranDescription'] = $global->get_param('UkuranDescription');
        $jahit->master['Flag'] = $global->get_param('Flag');   
        $sv = $jahit->save_items();
        $idx = $jahit->get_items(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "createDet":
        $jahit->master['ItemDetId'] = $global->get_param('ItemDetId');
        $jahit->master['UkuranDetailCode'] = $global->get_param('UkuranDetailCode');
        $jahit->master['UkuranIdDet'] = $global->get_param('UkuranIdDet');
        $jahit->master['UkuranDetailDescription'] = $global->get_param('UkuranDetailDescription');
        $jahit->master['FlagDet'] = $global->get_param('FlagDet');   
        $sv = $jahit->save_items_det();
        $idx = $jahit->get_items_det(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "update":
        $jahit->master['UkuranId'] = $global->get_param('UkuranId');
        $jahit->master['UkuranDescription'] = $global->get_param('UkuranDescription');
        $jahit->master['Flag'] = $global->get_param('Flag');
        
        $sv = $jahit->update_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "updateDet":
        $jahit->master['ItemDetId'] = $global->get_param('ItemDetId');
        $jahit->master['UkuranDetailCode'] = $global->get_param('UkuranDetailCode');
        $jahit->master['UkuranIdDet'] = $global->get_param('UkuranIdDet');
        $jahit->master['UkuranDetailDescription'] = $global->get_param('UkuranDetailDescription');
        $jahit->master['FlagDet'] = $global->get_param('FlagDet');   
        
        $sv = $jahit->update_items_det();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "delete":
        $jahit->master['UkuranId'] = $global->get_param('UkuranId');
        
        $sv = $jahit->delete_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "deleteDet":
        $jahit->master['ItemDetId'] = $global->get_param('ItemDetId');
        
        $sv = $jahit->delete_items_det();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "opt":
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
}
print json_encode($result);
$conect->closeDb($con);
?>
