<?php

/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include './inc/session.php';
include './inc/gabungan.php';

//$user = new user();
$conect = new connection();
$global = new globalfunc();
$excel = new PHPExcel();

$excel->getProperties()->setCreator("Indah Jaya, PT.")
                       ->setLastModifiedBy("Indah Jaya, PT.")
                       ->setDescription("Convert Log SMARTMRTX To Excel")
                       ->setKeywords("office 2007 openxml php")
                       ->setCategory("Result Converter SMARTMRTX");

$con = $conect->openDb();

if(isset($_POST['cmdUpload']))
{
    if ($_FILES["txtFile"]["error"] > 0)
    {
        echo "Error: " . $_FILES["txtFile"]["error"] . "<br>";
    }
    else
    {
        $fileName = $_FILES['txtFile']['name'];
        $hsl = array();
        if(stristr($fileName, ".ede"))
        {
            $arrF = file($_FILES['txtFile']['tmp_name']);
            $tempL = array();
            $rw = 1;
            $excel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$rw, 'Emp No')
                            ->setCellValue('B'.$rw, 'Name')
                            ->setCellValue('C'.$rw, 'Design')
                            ->setCellValue('D'.$rw, 'Sta')
                            ->setCellValue('E'.$rw, 'OpCode')
                            ->setCellValue('F'.$rw, 'Pcs')
                            ->setCellValue('G'.$rw, 'Rate(Rp)')
                            ->setCellValue('H'.$rw, 'N.W.T.(Rp)')
                            ->setCellValue('I'.$rw, 'O.T.(Rp)')
                            ->setCellValue('J'.$rw, 'P.T.')
                            ->setCellValue('K'.$rw, 'Reward(Rp)')
                            ->setCellValue('L'.$rw, 'Total(Rp)')
                            ->setCellValue('M'.$rw, 'Recount');
            $curent = array(
                'dataNow' => '',
                'dataBefore' => '',
                'rowNumNow' => 0,
                'rowNumNow' => 0
            );
            $dataL = array();
            for($i=0 ; $i<count($arrF) ; $i++)
            {
                $row = explode(",", $arrF[$i]);
                if($row[0]=="@")
                {
                    $rw += 1;
                    $curent['dataNow'] = ltrim(rtrim($row[1]));
                    $curent['rowNumNow'] = $rw;
                    $excel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$rw, ltrim(rtrim($row[1])))
                                ->setCellValue('B'.$rw, ltrim(rtrim($row[2])))
                                ->setCellValue('C'.$rw, ltrim(rtrim($row[10])))
                                ->setCellValue('D'.$rw, intval(ltrim(rtrim($row[5]))))
                                ->setCellValue('E'.$rw, ltrim(rtrim($row[6])))
                                ->setCellValue('F'.$rw, ltrim(rtrim($row[14])))
                                ->setCellValue('G'.$rw, ltrim(rtrim($row[8])))
                                ->setCellValue('H'.$rw, ltrim(rtrim($row[9])))
                                ->setCellValue('I'.$rw, '---')
                                ->setCellValue('J'.$rw, ltrim(rtrim($row[15])))
                                ->setCellValue('K'.$rw, ltrim(rtrim($row[16])))
                                ->setCellValue('L'.$rw, intval(ltrim(rtrim($row[15]))) * intval(ltrim(rtrim($row[16]))))
                                ->setCellValue('M'.$rw, ltrim(rtrim($row[19])));
                    
                    if($i!=count($arrF)-1)
                    {
                        $nextRow = explode(",", $arrF[$i+1]);
                        if($nextRow[0]=="L")
                        {
                            $rw += 1;
                            $excel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$rw, '')
                                    ->setCellValue('B'.$rw, '')
                                    ->setCellValue('C'.$rw, '---')
                                    ->setCellValue('D'.$rw, '') //merge with c
                                    ->setCellValue('E'.$rw, 'Total')
                                    ->setCellValue('F'.$rw, '=SUM(F'.$curent['rowNumBefore'].':F'.$curent['rowNumNow'].')')
                                    ->setCellValue('G'.$rw, '---')
                                    ->setCellValue('H'.$rw, 'Grade')
                                    ->setCellValue('I'.$rw, $dataL['grade'].'('.$dataL['pType'].')')
                                    ->setCellValue('J'.$rw, '---')
                                    ->setCellValue('K'.$rw, 'Total')
                                    ->setCellValue('L'.$rw, '=SUM(L'.$curent['rowNumBefore'].':L'.$curent['rowNumNow'].')')
                                    ->setCellValue('M'.$rw, 'Recount'.$dataL['recount']);
                            $excel->getActiveSheet()->mergeCells('A'.$curent['rowNumBefore'].':A'.($curent['rowNumNow']+1));
                            $excel->getActiveSheet()->mergeCells('B'.$curent['rowNumBefore'].':B'.($curent['rowNumNow']+1)); 
                        }
                    }
                    
                    if($i==count($arrF)-1)
                    {
                        
                        $rw += 1;
                        $excel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$rw, '')
                                ->setCellValue('B'.$rw, '')
                                ->setCellValue('C'.$rw, '---')
                                ->setCellValue('D'.$rw, '') //merge with c
                                ->setCellValue('E'.$rw, 'Total')
                                ->setCellValue('F'.$rw, '=SUM(F'.$curent['rowNumBefore'].':F'.$curent['rowNumNow'].')')
                                ->setCellValue('G'.$rw, '---')
                                ->setCellValue('H'.$rw, 'Grade')
                                ->setCellValue('I'.$rw, $dataL['grade'].'('.$dataL['pType'].')')
                                ->setCellValue('J'.$rw, '---')
                                ->setCellValue('K'.$rw, 'Total')
                                ->setCellValue('L'.$rw, '=SUM(L'.$curent['rowNumBefore'].':L'.$curent['rowNumNow'].')')
                                ->setCellValue('M'.$rw, 'Recount'.$dataL['recount']);
                        $excel->getActiveSheet()->mergeCells('A'.$curent['rowNumBefore'].':A'.($curent['rowNumNow']+1));
                        $excel->getActiveSheet()->mergeCells('B'.$curent['rowNumBefore'].':B'.($curent['rowNumNow']+1)); 
                        
                    }
                    
                    if($curent['dataNow']==$curent['dataBefore'])
                    {
                        $excel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$curent['rowNumNow'],'')
                                    ->setCellValue('B'.$curent['rowNumNow'],'');
                        $excel->getActiveSheet()->mergeCells('A'.$curent['rowNumBefore'].':A'.$curent['rowNumNow']);
                        $excel->getActiveSheet()->mergeCells('B'.$curent['rowNumBefore'].':B'.$curent['rowNumNow']);                       
                        
                    }
                    else 
                    {
                        $curent['dataBefore'] = ltrim(rtrim($row[1]));
                        $curent['rowNumBefore'] = $rw;
                    }
                }
                else if($row[0]=="L")
                {
                    $dataL['grade'] = ltrim(rtrim($row[11]));
                    $dataL['pType'] = ltrim(rtrim($row[15]));
                    $dataL['recount'] = ltrim(rtrim($row[19]));
                }
            }
//            print_r($hsl);
            $excel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$_FILES['txtFile']['name'].'.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        else
            echo "not alowed";
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?=TITLE?></title>
        <?=$global->include_style()?>
        <?=$global->include_js()?>
        <!--<script src="js/app/upload.js"></script>-->
    </head>
    <body>
        <div id="header">
            <div class="shell">
                <div id="top">
                    <h1><img src="./images/ij.png"></img></h1>
                    <div id="top-navigation">
                        <?=SELAMAT_DATANG?> <a href="#"><strong><? //$_SESSION['UserName']?></strong></a>
<!--                        <span>|</span>
                        <a href="home.php" class="menuUtama">Home</a>
                        <span>|</span>
                        <a href="logout" class="lgout">Log out</a>-->
                    </div>
                </div>
            </div>
        </div>
        <div id="container">
            <div class="shell">
                <div class="box centertb" style="width: 350px;">
                    <div class="box-head"><h2>SMART MRTX Report Converter v.1</h2></div>
                        <form method="post" name="frmLogin" enctype="multipart/form-data">
                            <div class="form">
                                <p class="inline-field">
                                    <label>Upload File:</label>
                                    <input type="file" class="field" name="txtFile" id="txtFile" style="width: 320px;"></input>
                                </p>                       
                            </div>
                            <div class="buttons">                                
                                <input type="submit" id="cmdUpload" name="cmdUpload" value="Convert" style="width: 100px; height: 30px;" />
                            </div>
                        </form>
                </div>
                <?
                if(!empty($error))
                    $global->alert_ui($error);
                ?>
            </div>
        </div>
        <div id="footer">
            <div class="shell">
                <span class="left">&copy; 2013 - Indah Jaya, PT.</span>		
            </div>
        </div>
    </body>
</html>
<?
$conect->closeDb($con);
?>