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

$jahit = new jenis_jahit();

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
    case "create":
        $jahit->master['JenisId'] = $global->get_param('JenisId');
        $jahit->master['JenisDescription'] = $global->get_param('JenisDescription');   
        $jahit->master['Flag'] = $global->get_param('Flag');   
        $sv = $jahit->save_items();
        $idx = $jahit->get_items(true);
        $result['Result'] = $sv['res']; 
        $result['Record'] = $idx;
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "update":
        $jahit->master['JenisId'] = $global->get_param('JenisId');
        $jahit->master['JenisDescription'] = $global->get_param('JenisDescription');   
        $jahit->master['Flag'] = $global->get_param('Flag');   
        
        $sv = $jahit->update_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    case "delete":
        $jahit->master['JenisId'] = $global->get_param('JenisId');
        
        $sv = $jahit->delete_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
    
}
print json_encode($result);
$conect->closeDb($con);
?>
