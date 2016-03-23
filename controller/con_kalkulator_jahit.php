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

$jahit = new hasil_jahit();

$jahit->master['ukuran'] = $global->get_param("txt",FALSE);
$jahit->master['jenis'] = $global->get_param("cmbJenisJahit",FALSE);
$jahit->master['jam'] = $global->get_param("cmbJamKerja",FALSE);

$result = array();

$result = $jahit->hitung_premi_calc();
//$result["msg"] = $txt[11];


print json_encode($result);
$conect->closeDb($con);
?>
