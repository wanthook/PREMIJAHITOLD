<?php

/*
 * create by Taufiq Hari widodo
 */
include 'define.php';

$strDir  = __DIR__;
$strDir = str_replace("inc", "class", $strDir);
ini_set('include_path', $strDir);
//ini_set('include_path', '');

include '/connection.inc.php';
include '/globalfunc.inc.php';
include '/PHPExcel.php';
include '/user.inc.php';
include '/design.inc.php';
include '/hanging.inc.php';
include '/karyawan.inc.php';
include '/jenis_jahit.inc.php';
include '/index_jahit.inc.php';
include '/index_jahit_libur.inc.php';
include '/ukuran.inc.php';
include '/hasil_jahit.inc.php';
include '/hasil_jahit_libur.inc.php';
?>
