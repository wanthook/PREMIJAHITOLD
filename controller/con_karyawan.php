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

$jahit = new karyawan();

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
    case "create":
        $jahit->master['KaryawanId'] = $global->get_param('KaryawanId');
        $jahit->master['PinId'] = $global->get_param('PinId');
        $jahit->master['KaryawanName'] = $global->get_param('KaryawanName'); 
        $jahit->master['HangingId'] = $global->get_param('HangingId');
        $jahit->master['Flag'] = $global->get_param('Flag');   
        $sv = $jahit->save_items();
        $idx = $jahit->get_items(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "update":
        $jahit->master['KaryawanId'] = $global->get_param('KaryawanId');
        $jahit->master['PinId'] = $global->get_param('PinId');
        $jahit->master['KaryawanName'] = $global->get_param('KaryawanName'); 
        $jahit->master['HangingId'] = $global->get_param('HangingId');
        $jahit->master['Flag'] = $global->get_param('Flag');
        
        $sv = $jahit->update_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "delete":
        $jahit->master['PinId'] = $global->get_param('PinId');
        
        $sv = $jahit->delete_items();
        
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
