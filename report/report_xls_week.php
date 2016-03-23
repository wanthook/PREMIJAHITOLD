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

$excel->getProperties()->setCreator("Indah Jaya, PT.")
                       ->setLastModifiedBy("Indah Jaya, PT.")
                       ->setDescription("Report Weekly Jahit")
                       ->setKeywords("office 2007 openxml php")
                       ->setCategory("Report Weekly Jahit");

$con = $conect->openDb();

$dt = $global->get_param("txtDate");
$hg = $global->get_param("cmbHanging");

$rw = 1;

if(!empty($dt) && !empty($hg))
{
    
    $excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$rw, 'DAFTAR PENGAJUAN INSENTIF JAHIT TARGET SISTEM HANGING BULAN '.strtoupper($global->getSpellDate($dt)));
    
    $excel->getActiveSheet()->mergeCells('A'.$rw.':J'.$rw);
    
    $sql = "select * from jht_master_hanging where 1=1 ";
    if($hg!="all")
        $sql .= " and hanging_id='".$hg."' ";
    
    $sql .= " order by hanging_id asc";
    
    $res = mysql_query($sql);
    while($row = mysql_fetch_assoc($res))
    {
        
        
        $rw+=2;
        
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$rw, $row['hanging_desc'])
            ->setCellValue('D'.$rw, 'Jml Rp')
            ->setCellValue('E'.$rw, 'Jml Rp')
            ->setCellValue('F'.$rw, 'Jml Rp')
            ->setCellValue('G'.$rw, 'Jml Rp')
            ->setCellValue('H'.$rw, 'Jml Rp')
            ->setCellValue('I'.$rw, 'Total')
            ->setCellValue('J'.$rw, 'Tanda Terima');
        $excel->getActiveSheet()->mergeCells('A'.$rw.':C'.$rw);
        $excel->getActiveSheet()->mergeCells('J'.$rw.':K'.$rw);
        
        $rw+=1;
        
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$rw, 'No')
            ->setCellValue('B'.$rw, 'Nama')
            ->setCellValue('C'.$rw, 'PIN')
            ->setCellValue('D'.$rw, 'Mgu Ke 1')
            ->setCellValue('E'.$rw, 'Mgu Ke 2')
            ->setCellValue('F'.$rw, 'Mgu Ke 3')
            ->setCellValue('G'.$rw, 'Mgu Ke 4')
            ->setCellValue('H'.$rw, 'Mgu Ke 5')
            ->setCellValue('I'.$rw, 'INST');
        
        $sqlR = "select week(a.transaksi_hasil_jahit_date,1) mingguke,
                             b1.karyawan_pin,
                             b1.karyawan_nama,
                             sum(b.transaksi_hasil_jahit_detail_premi) premi
               from jht_transaksi_hasil_jahit a,
                         jht_transaksi_hasil_jahit_detail b
                               left join jht_master_karyawan b1 on b.karyawan_id = b1.karyawan_id
               where a.transaksi_hasil_jahit_id = b.transaksi_hasil_jahit_id
                               and a.hanging_id = '".$row['hanging_id']."'
                               and a.transaksi_hasil_jahit_date between ('$dt-22' - interval 1 month) and '$dt-21'
               group by mingguke, b.karyawan_id
               order by karyawan_nama asc";
        $resR = mysql_query($sqlR);
        $tampung = array();        
        $awalData = $rw+1;
        $sumTot = 0;
        //$first = true;
        $rw+=1;
        while($rowR = mysql_fetch_assoc($resR))
        {
            $tampung[$rowR['mingguke']][] = array("pin" => $rowR['karyawan_pin'],
                                                "nama" => $rowR['karyawan_nama'],
                                                "premi" => $rowR['premi']);
        }
        
        $first = true;
        $rwt = 0;
        $no = 1;
        $char = 68; //char d
        $rows = 0;
        foreach($tampung as $key=>$value)
        {
            $rwt = $rw;
            if($first)
            {
                $rows = count($value);
                for($j=0 ; $j<count($value) ; $j++)
                {
                    $excel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$rwt, $no)
                            ->setCellValue('B'.$rwt, $value[$j]['nama'])
                            ->setCellValue('C'.$rwt, strval($value[$j]['pin']))
                            ->setCellValue(chr($char).$rwt, $value[$j]['premi']);
                    $rwt += 1;
                    $no+=1;
                }
            }
            else
            {
                for($j=0 ; $j<count($value) ; $j++)
                {
                    $excel->setActiveSheetIndex(0)
                            ->setCellValue(chr($char).$rwt, $value[$j]['premi']);
                    $rwt += 1;
                }
            }
            $char+=1;
            $first = false;
        }
        
        $no = 1;
        $rwt = $rw;
        for($j = 0 ; $j < $rows ; $j++)
        {
            $col = "J";
            
            if($no%2==0)
                $col = "K";
            
            $excel->setActiveSheetIndex(0)
                  ->setCellValue('I'.$rwt, '=sum(D'.$rwt.':H'.$rwt.')')
                  ->setCellValue($col.$rwt, "$no");
            $rwt+=1;
            $no++;
        }
        
        $rw = $rwt;
//        $excel->setActiveSheetIndex(0)
//                ->setCellValue('A'.$rw, print_r($tampung));
//        $rw+=1;
//        $excel->setActiveSheetIndex(0)
//                ->setCellValue('B'.$rw, 'Total Insentif '.$row['NmHanging'])
//                ->setCellValue('G'.$rw, 'Rp.')
//                ->setCellValue('H'.$rw, $sumTot);
        
        
    }
    
    $excel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$hg."-".$dt.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}


$conect->closeDb($con);
?>
