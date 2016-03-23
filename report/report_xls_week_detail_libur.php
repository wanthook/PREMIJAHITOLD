<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include '../inc/session.php';
include '../inc/gabungan.php';

//$user = new user();
$conect = new connection();
$global = new globalfunc();
$excel = new PHPExcel();
$rich = new PHPExcel_RichText();

$excel->getProperties()->setCreator("Indah Jaya, PT.")
                       ->setLastModifiedBy("Indah Jaya, PT.")
                       ->setDescription("Report Detail Weekly Jahit Libur")
                       ->setKeywords("office 2007 openxml php")
                       ->setCategory("Report Detail Weekly Jahit");

$con = $conect->openDb();

$dt = $global->get_param("txtDate");
$hg = $global->get_param("cmbHanging");

//get ukuran detail
$ukuran = new ukuran();
$ukuran->src = "bla";
$ukuranDet = $ukuran->get_items_det();

//set hanging
$hanging = new hanging();

//jenis jam kerja
$jam = $global->get_app_ref("APP", "TMR");
for($i=0 ; $i<count($jam) ; $i++)
{
    $jam[$i]["id"] = $jam[$i]["ref_value"];
    $jam[$i]["desc"] = $jam[$i]["ref_description"];

    unset($jam[$i]["ref_description"]);
    unset($jam[$i]["ref_value"]);
}
//$jam = array
//       (
//            array("id"=>"N","desc"=>"NORMAL"),
//            array("id"=>"P","desc"=>"PENDEK")
//       );

$styleArray = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		),
	),
);

$styleFont = array('font' =>
                        array('color' =>
                          array('rgb' => '000000'),
                          'bold' => true,
                        ),
               'alignment' => array(
                                'wrap'       => true,
                          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                          'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                            ),
            );

$styleFont2 = array('font' =>
                        array('color' =>
                          array('rgb' => '000000'),
                          'bold' => true,
                        ),
               'alignment' => array(
                                'wrap'       => true,
                          'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                            ),
            );

$styleFont3 = array('font' =>
                        array('color' =>
                          array('rgb' => '000000'),
                          'bold' => true,
                        ),
               'alignment' => array(
                                'wrap'       => true,
                          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                          'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                            ),
            );

//jenis jahit
$jj = new jenis_jahit();
$jj->src = "act";
$jenisJahit = $jj->get_items();

$excel->removeSheetByIndex(0);

if(!empty($dt) && !empty($hg))
{
    //variable menampung hasil query di db
    $data = array();
    
    //cek hangingnya apa
    $flt = "";
    if($hg!="all")
        $flt = " and a.hanging_id='".$hg."' ";
    
    //============ Start ambil semua data per bulan di pilih ==================//
    $sql = "select a.transaksi_hasil_jahit_date,
                   a.hanging_id,
                   b.karyawan_id,
                   a.jenis_id,
                   c.ukuran_detail_id,
                   c.jam_kerja,
                   c.jumlah,
                   b.transaksi_hasil_jahit_detail_jumlah,
                   c.jumlah*c.premi premikali,
                   c.premi xpremi
           from jht_transaksi_hasil_jahit_libur a
                    left join jht_master_jenis_jahit a1 on a.jenis_id = a1.jenis_id,
                jht_transaksi_hasil_jahit_libur_detail b,
                jht_transaksi_hasil_jahit_libur_detail_ukuran c
                    left join jht_master_ukuran_detail c1 on c.ukuran_detail_id = c1.ukuran_detail_id
           where a.transaksi_hasil_jahit_id = b.transaksi_hasil_jahit_id
                 and b.transaksi_hasil_jahit_detail_id = c.transaksi_hasil_jahit_detail_id
                 and a.transaksi_hasil_jahit_date between ('$dt-22' - interval 1 month) and '$dt-21'
                 $flt
           order by transaksi_hasil_jahit_date, hanging_id,karyawan_id, ukuran_detail_id asc";
    $res = mysql_query($sql);
    while($row = mysql_fetch_assoc($res))
    {
        $data[$row['transaksi_hasil_jahit_date']]
             [$row['hanging_id']]
             [$row['karyawan_id']]
             [$row['jenis_id']]
             [$row['ukuran_detail_id']]
             [$row['jam_kerja']] = array("jumlah"=>$row['jumlah'],
                                         "premi"=>$row['premikali'],
                                         "pcs" => $row['xpremi']);
                                       //  "pcs" => $row['transaksi_hasil_jahit_detail_jumlah']);
    }
    
    //======== start masukin ke excel =======//
    if(!empty($data))
    {
        //======== start perulangan tanggal yang masuk ke sheet excel =======//
        
        //variable sheet ke
        $sheetKe = 0;
        $iterationDate = 0;
        $arrJum = array();
        foreach ($data as $key=>$value)
        {
            
            //======= start create sheet baru dengan nama tanggal =========//
            $dateSheet = new DateTime($key);            
            $ws = new PHPExcel_Worksheet($excel, $dateSheet->format("d"));            
            $excel->addSheet($ws, $sheetKe);
            //======= end create sheet baru dengan nama tanggal =========//
            
            //set sheet yang dikerjakan berdasarkan tanggal yang sedang dikerjakan
            $excel->setActiveSheetIndex($sheetKe);
            
            $cl = 0; //variable kolom
            $rw = 1; //variable baris
            
            //$kor = $excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw)->getCoordinate();
            //====== start judul sheet per tanggal =========//
            $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,"PENGAJUAN DANA INSENTIF TARGET JAHIT")
                                    ->setCellValueByColumnAndRow($cl,$rw+=1,"PERIODE : ".strtoupper($global->getSpellDate($dt)));
            //====== end judul sheet per tanggal =========//
            
            foreach ($value as $keyHg=>$valueHg)
            {
                $cl = 0;
                
                $hanging->src = "id";
                $hanging->master['id'] = $keyHg;
                $retHanging = $hanging->get_items();
                
                $rw += 2;
                $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$retHanging[0]['HangingDescription']);
                $rw+=1;
                
                $sRw = $rw;                
                $sCl = 0;
                //======== start membuat head column ========//
//                $hNo = $rich->createTextRun("No.")->getFont()->;
                $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,'NO.')
                                        ->mergeCellsByColumnAndRow($cl, $rw, $cl, $rw+1)
                                        ->setCellValueByColumnAndRow($cl+=1,$rw,'NAMA')
                                        ->mergeCellsByColumnAndRow($cl, $rw, $cl, $rw+1)
                                        ->setCellValueByColumnAndRow($cl+=1,$rw,'PIN')
                                        ->mergeCellsByColumnAndRow($cl, $rw, $cl, $rw+1)
                                        ->setCellValueByColumnAndRow($cl+=1,$rw,'UKURAN')
                                        ->mergeCellsByColumnAndRow($cl, $rw, $cl+1, $rw+1);                
                $cl+=2;
                foreach($jam as $kJam=>$vJam)
                {
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$vJam['desc'])
                                            ->mergeCellsByColumnAndRow($cl, $rw, ($cl+count($jenisJahit)-1), $rw);
                    $rw+=1;
                    foreach($jenisJahit as $kJenisJahit => $vJenisJahit)
                    {
                        $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$vJenisJahit['JenisDescription']);
                        $cl+=1;
                    }
                    $rw-=1;
                    //$cl+=count($jenisJahit)+1;
                }
                
                //style header
                $excel->getActiveSheet()->getStyle
                        (
                        $excel->getActiveSheet()->getCellByColumnAndRow(0, $rw)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl-1, $rw+1)->getCoordinate()
                        )->applyFromArray($styleFont);
                $sCl = $cl-1;
                //======== end membuat head column ========//
                
                //======== start membuat isi ==============//
                
                //persiapan membuat list karyawan berdasarkan hanging yang terpilih dalam perulangan
                $kar = new karyawan();
                $kar->src="act";
                $kar->master["HangingId"]=$retHanging[0]['HangingId'];
                $karyawan = $kar->get_items(); //list karyawan ditampung di variable $karyawan
                
                //variable penomeran
                $nomor = 1;
                $rw+=1;
                $kCt = 0;
                foreach($karyawan as $kKaryawan => $vKaryawan)
                {
                    $cl = 0;
                    if($kCt<1)
                        $rw+=1;
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$nomor)
                                            ->mergeCellsByColumnAndRow($cl, $rw, $cl, $rw+count($ukuranDet)+2)
                                            ->setCellValueByColumnAndRow($cl+=1,$rw,$vKaryawan['KaryawanName'])
                                            ->mergeCellsByColumnAndRow($cl, $rw, $cl, $rw+count($ukuranDet)+2)
                                            ->setCellValueByColumnAndRow($cl+=1,$rw,$vKaryawan['PinId'])
                                            ->mergeCellsByColumnAndRow($cl, $rw, $cl, $rw+count($ukuranDet)+2);
                    //style
                    $excel->getActiveSheet()->getStyle
                    (
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl-2, $rw)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw)->getCoordinate()
                    )->applyFromArray($styleFont);
                    
                    $cRw = $rw;
                    $cl+=1;
                    $cCl = $cl;
                    $iteration = 0;
                    $countIterasion = count($ukuranDet);
                    $premiArr = array();
                    $pcsArr = array();
                    foreach($ukuranDet as $kUkuran => $vUkuran)
                    {
                        
                        $cl = $cCl;
                        $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$vUkuran['UkuranDetailCode'])
                                                ->setCellValueByColumnAndRow($cl+1,$rw,$vUkuran['UkuranDetailDescription']);
                        $cl = $cCl+1;
                        foreach($jam as $kJam=>$vJam)
                        {
                            foreach($jenisJahit as $kJenisJahit => $vJenisJahit)
                            {
                                $nilai = 0;
                                if(isset($valueHg[$vKaryawan['KaryawanId']]
                                                 [$vJenisJahit['JenisId']]
                                                 [$vUkuran['ItemDetId']]
                                                 [$vJam['id']]))
                                {
                                    $nilai = $valueHg[$vKaryawan['KaryawanId']]
                                                 [$vJenisJahit['JenisId']]
                                                 [$vUkuran['ItemDetId']]
                                                 [$vJam['id']];
                                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl+=1,$rw,$nilai['jumlah']);
                                    $premiArr[$vJam['id']][$vJenisJahit['JenisId']] = $nilai['premi'];
                                    $pcsArr[$vJam['id']][$vJenisJahit['JenisId']] = $nilai['pcs'];
                                }
                                else
                                {
                                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl+=1,$rw,"0");
                                }
                            }
                        }
                        
                        if($iteration == $countIterasion-1)
                        {
                            $cl = $cCl;
                            $rw+=1;
                            //=================== start total pcs =======================//
                            $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,"X @ (Rp.)")
                                                    ->mergeCellsByColumnAndRow($cl, $rw, $cl+=1, $rw);
                            
                            
//                            for($i=0 ; $i<(count($jam)*count($jenisJahit)) ; $i++)
//                            {
//                                $excel->getActiveSheet()->setCellValueByColumnAndRow($cl+=1,$rw,"=SUM(".
//                                        $excel->getActiveSheet()->getCellByColumnAndRow($cl, $cRw)->getCoordinate().":".$excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw-1)->getCoordinate().
//                                        ")");
//                            }
                            //=================== end total pcs ========================//
                            
                            //=================== start premi =======================//
                            $cl=$cCl;
//                            $rw+=1;
                            $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw+1,"PREMI (Rp.)")
                                                    ->mergeCellsByColumnAndRow($cl, $rw+1, $cl+=1, $rw+1);
                            foreach($jam as $kJam=>$vJam)
                            {
                                foreach($jenisJahit as $kJenisJahit => $vJenisJahit)
                                {
                                    $prem = 0;
                                    $pcs = 0;
                                    
                                    if(isset($pcsArr[$vJam['id']][$vJenisJahit['JenisId']]))
                                        $pcs = $pcsArr[$vJam['id']][$vJenisJahit['JenisId']];
                                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl+=1,$rw,$pcs);
                                    
                                    if(isset($premiArr[$vJam['id']][$vJenisJahit['JenisId']]))
                                        $prem = $premiArr[$vJam['id']][$vJenisJahit['JenisId']];
                                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw+1,$prem);
                                }
                            }
                            //=================== end premi =======================//
                            
                            //=================== start total semuanya ===============//
                            $cl=$cCl;
                            $rw+=2;
                            $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,"TOTAL PREMI (Rp.)")
                                                    ->mergeCellsByColumnAndRow($cl, $rw, $cl+=1, $rw)
                                                    ->setCellValueByColumnAndRow($cl+=1,$rw,"=SUM(".$excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw-1)->getCoordinate().
                                                                                ":".
                                                                                $excel->getActiveSheet()->getCellByColumnAndRow($cl+(count($jam)*count($jenisJahit))-1, $rw-1)->getCoordinate().")")
                                                    ->mergeCellsByColumnAndRow($cCl+2, $rw, $cCl+(count($jam)*count($jenisJahit))+1, $rw);
                            
                            
                            
                            if(!isset($arrJum[$keyHg][$vKaryawan['KaryawanId']]))
                                $arrJum[$keyHg][$vKaryawan['KaryawanId']] = $excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw)->getCalculatedValue();
                            else
                                $arrJum[$keyHg][$vKaryawan['KaryawanId']] += $excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw)->getCalculatedValue();
                            
                            //=================== end total semuanya   ===============//
                            
                            //style
                            $excel->getActiveSheet()->getStyle
                            (
                                $excel->getActiveSheet()->getCellByColumnAndRow($cCl, $rw-2)->getCoordinate().
                                ":".
                                $excel->getActiveSheet()->getCellByColumnAndRow($cCl, $rw)->getCoordinate()
                            )->applyFromArray($styleFont2);
                            
                            //style
                            $excel->getActiveSheet()->getStyle
                            (
                                $excel->getActiveSheet()->getCellByColumnAndRow($cCl+2, $rw-2)->getCoordinate().
                                ":".
                                $excel->getActiveSheet()->getCellByColumnAndRow($cCl+2+(count($jam)*count($jenisJahit)), $rw-1)->getCoordinate()
                            )->applyFromArray($styleFont);
                            $excel->getActiveSheet()->getStyle
                            (
                                $excel->getActiveSheet()->getCellByColumnAndRow($cCl+2, $rw)->getCoordinate()
                            )->applyFromArray($styleFont);
                            
                        }
                        else
                            $rw+=1;
                        $iteration+=1;
                    }
                    
                    $rw=$cRw;
                    $nomor+=1;
                    $rw+=count($ukuranDet)+3;
                    $kCt+=1;
                }                
                //======== start membuat isi ==============//
                $excel->getActiveSheet()->getStyle(
                        $excel->getActiveSheet()->getCellByColumnAndRow(0, $sRw)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($sCl, $rw-1)->getCoordinate())->applyFromArray($styleArray);
                //$excel->getActiveSheet()->getStyleByColumnAndRow($prem, $i)
            }
            
            $sheetKe += 1;
            
            if($iterationDate == count($data)-1)
            {
//                print_r($arrJum);
                
                $ws = new PHPExcel_Worksheet($excel, "SUMMARY TOTAL");            
                $excel->addSheet($ws, $sheetKe);
                
                $excel->setActiveSheetIndex($sheetKe);
                
                $cl = 0; //variable kolom
                $rw = 1; //variable baris

                //$kor = $excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw)->getCoordinate();
                //====== start judul sheet per tanggal =========//
                $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,"PENGAJUAN DANA INSENTIF TARGET JAHIT")
                                        ->setCellValueByColumnAndRow($cl,$rw+=1,"PERIODE : ".strtoupper($global->getSpellDate($dt)));
                
                foreach ($arrJum as $kJum => $vJum)
                {
                    $hanging->master['id'] = $kJum;
                    $retHanging = $hanging->get_items();
                    
                    $cl = 0;
                    $rw += 2;
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$retHanging[0]['HangingDescription']);
                    $rw+=1;
                    $sRw = $rw;
                    
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,'NO.')
                                            ->setCellValueByColumnAndRow($cl+=1,$rw,'NAMA')
                                            ->setCellValueByColumnAndRow($cl+=1,$rw,'PIN')
                                            ->setCellValueByColumnAndRow($cl+=1,$rw,'PREMI (RP)')
                                            ->setCellValueByColumnAndRow($cl+=1,$rw,'TANDA TERIMA')
                                            ->mergeCellsByColumnAndRow($cl, $rw, $cl+1, $rw);
                    $excel->getActiveSheet()->getStyle
                    (
                        $excel->getActiveSheet()->getCellByColumnAndRow(0, $rw)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl,$rw)->getCoordinate()
                    )->applyFromArray($styleFont);
                    
                    $kar->src="act";
                    $kar->master["HangingId"]=$retHanging[0]['HangingId'];
                    $karyawan = $kar->get_items();
                    
                    $nomor = 1;
                    $rw+=1;
                    $cRw = $rw;
                    foreach($karyawan as $kKar => $vKar)
                    {
                        $cl = 0;
                        $addParaf = 1;
                        
                        if($nomor%2==0)
                            $addParaf = 2;
                        
                        
                        $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,$nomor)
                                                ->setCellValueByColumnAndRow($cl+=1,$rw,$vKar['KaryawanName'])
                                                ->setCellValueByColumnAndRow($cl+=1,$rw,$vKar['PinId'])
                                                ->setCellValueByColumnAndRow($cl+=1,$rw,$vJum[$vKar['KaryawanId']])
                                                ->setCellValueByColumnAndRow($cl+$addParaf,$rw,$nomor);
                        $nomor+=1;
                        $rw+=1;
                    }
                    
                    $excel->getActiveSheet()->getStyle
                    (
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl+1, $sRw+1)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl+2,$rw)->getCoordinate()
                    )->applyFromArray($styleFont3);
                    
                    
                    $cl = 0;
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($cl,$rw,"")
                                                ->setCellValueByColumnAndRow($cl+=1,$rw,"TOTAL")
                                                ->mergeCellsByColumnAndRow($cl, $rw, $cl+1, $rw)
                                                ->setCellValueByColumnAndRow($cl+=2,$rw,"=SUM(".
                                                        $excel->getActiveSheet()->getCellByColumnAndRow($cl, $cRw)->getCoordinate().
                                                        ":".
                                                        $excel->getActiveSheet()->getCellByColumnAndRow($cl, $rw-1)->getCoordinate().")");
//                  
                    $excel->getActiveSheet()->getStyle
                    (
                        $excel->getActiveSheet()->getCellByColumnAndRow(0, $rw)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl+2,$rw)->getCoordinate()
                    )->applyFromArray($styleFont);
                    
                    //bikin garis
                    $excel->getActiveSheet()->getStyle
                    (
                        $excel->getActiveSheet()->getCellByColumnAndRow(0, $sRw)->getCoordinate().
                        ":".
                        $excel->getActiveSheet()->getCellByColumnAndRow($cl+2,$rw)->getCoordinate()
                    )->applyFromArray($styleArray);
                    
                    $rw+=1;
                                    
                    $sCl = 0;
                }
            }
            else 
            {
                 $iterationDate += 1;
            }
            
        }
        //======== end perulangan tanggal yang masuk ke sheet excel =======//
        
    }
    //======== end masukin ke excel =======//
//    //======== end perulangan hanging ================//
    $excel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$hg."-".$dt.'.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    
    $excel->disconnectWorksheets();
    unset($excel);
//    exit;
}


$conect->closeDb($con);
exit;
?>
