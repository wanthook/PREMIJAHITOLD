<?php
/*
 * create by Taufiq Hari widodo
 */
include '../inc/session.php';
include '../inc/gabungan_1.php';

//include 'json_head.php';

$user = new user();
$conect = new connection();
$global = new globalfunc();

$jahit = new indexjahit();

$con = $conect->openDb();

$idx = $jahit->get_index_target();

echo json_encode($idx);

$conect->closeDb($con);
?>
