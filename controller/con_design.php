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

$jahit = new items();

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
        $jahit->master['DesignCode'] = $global->get_param('DesignCode');
        $jahit->master['DesignDescription'] = $global->get_param('DesignDescription');
        
        $sv = $jahit->save_items();
//        print_r($sv);
        $idx = $jahit->get_items(true);
        $result['Result'] = $sv['res'];
//	$result['Message'] = $sv['msg'];    
        $result['Record'] = $idx;
    break;
    case "update":
        $jahit->master['DesignId'] = $global->get_param('DesignId');
        $jahit->master['DesignCode'] = $global->get_param('DesignCode');
        $jahit->master['DesignDescription'] = $global->get_param('DesignDescription');
        
        $sv = $jahit->update_items();
        
        $result['Result'] = $sv['res'];
    break;
    case "delete":
        $jahit->master['DesignId'] = $global->get_param('DesignId');
        
        $sv = $jahit->delete_items();
        
        $result['Result'] = $sv['res'];
        if($sv['res']!="OK")
            $result["Message"] = $sv['msg'];
    break;
}
print json_encode($result);
$conect->closeDb($con);
?>
