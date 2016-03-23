<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of indexjahit
 *
 * @author taufiq
 */
class hasil_jahit 
{
    //Parameter Master
    public $master;
    
    //for searching
    public $start_rows;
    public $size_rows;
    public $order_by;
    public $src;
	
	public $additionalQuery;
    
    private $sqlSearch,$sqlSearchDet,$sqlSearchUkuranDet;
    private $tblName,$tblNameDet,$tblNameDetUkuran;
    
    function __construct() 
    {
        
        $this->tblName = "jht_transaksi_hasil_jahit";
        $this->tblNameDet = "jht_transaksi_hasil_jahit_detail";
        $this->tblNameDetUkuran = "jht_transaksi_hasil_jahit_detail_ukuran";
        
        $this->sqlSearch = "select a.* from ".$this->tblName." a where 1=1 ";
        $this->sqlSearchDet = "select jthjd.transaksi_hasil_jahit_detail_id,
                                      jthjd.transaksi_hasil_jahit_id,
                                      jthjd.karyawan_id,
                                      tmk.karyawan_pin,
                                      tmk.karyawan_nama,
                                      jthjd.ukuran_id,
                                      if(tmu.ukuran_desc is null or tmu.ukuran_desc='',0,tmu.ukuran_desc) ukuran_desc,
                                      jthjd.transaksi_hasil_jahit_detail_jumlah,
                                      jthjd.transaksi_hasil_jahit_detail_premi,
                                      jthjd.modifiy_date
                               from ".$this->tblName." jthj,
                                    ".$this->tblNameDet." jthjd
                                               left join jht_master_karyawan tmk on jthjd.karyawan_id = tmk.karyawan_id
                                               left join jht_master_ukuran tmu on jthjd.ukuran_id = tmu.ukuran_id
                               where jthj.transaksi_hasil_jahit_id = jthjd.transaksi_hasil_jahit_id";
        
        $this->sqlSearchUkuranDet = "select jthjdu.transaksi_hasil_jahit_detail_ukuran_id,
                                            jthjdu.transaksi_hasil_jahit_detail_id,
                                            jthjdu.ukuran_detail_id,
                                            jthjdu.jumlah,
                                            jthjdu.modifiy_date
                                    from jht_transaksi_hasil_jahit_detail_ukuran jthjdu
                                    where 1=1";
    }
    
    function get_items($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearch;
        
        if($prev==true)
            $sql .= " and a.transaksi_hasil_jahit_id='".$this->master['masId']."'";
        
        if($this->master['HasilJahitId'])
            $sql .= "and a.transaksi_hasil_jahit_id='".$this->master['HasilJahitId']."'";
			
		if($this->additionalQuery)
            $sql .= $this->additionalQuery;
			
        $sql .= " order by transaksi_hasil_jahit_date desc ";
        if(!$prev && (empty($this->src) || is_array($this->src)))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        echo $sql;
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            //$dtEx = explode($row['transaksi_hasil_jahit_date'], $string)
            
            if(!$prev)
            {
                $ret[] = Array(
                    "HasilJahitId" => $row['transaksi_hasil_jahit_id'],
                    "No" => $i,
                    "HasilJahitDate" => $row['transaksi_hasil_jahit_date'],
                    "JenisId" => $row['jenis_id'],
                    "IndexJamKerja" => $row['transaksi_hasil_jahit_jam_kerja'],
                    "HangingId" => $row['hanging_id'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "HasilJahitId" => $row['transaksi_hasil_jahit_id'],
                    "No" => $i,
                    "HasilJahitDate" => $row['transaksi_hasil_jahit_date'],
                    "JenisId" => $row['jenis_id'],
                    "IndexJamKerja" => $row['transaksi_hasil_jahit_jam_kerja'],
                    "HangingId" => $row['hanging_id'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            $i++;
            
        }    
        //print_r($ret);
        return $ret;
    }
    
    function get_items2()
    {
        $ret = array();
                
        $sql = $this->sqlSearch;
        
	if($this->additionalQuery)
            $sql .= $this->additionalQuery;
        //echo $sql;
        $res = mysql_query($sql);
        while($row = mysql_fetch_assoc($res))
        {   
            //$dtEx = explode($row['transaksi_hasil_jahit_date'], $string)
            $ret[] = Array(
                    "HasilJahitId" => $row['transaksi_hasil_jahit_id'],
                    "HasilJahitDate" => $row['transaksi_hasil_jahit_date'],
                    "JenisId" => $row['jenis_id'],
                    "IndexJamKerja" => $row['transaksi_hasil_jahit_jam_kerja'],
                    "HangingId" => $row['hanging_id'],
                    "LastEdit" => $row['modifiy_date']
                );
            
        }    
        //print_r($ret);
        return $ret;
    }
    
    function get_items_det($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearchDet;
        
        if($prev==true)
            $sql .= " and jthjd.transaksi_hasil_jahit_detail_id='".mysql_insert_id()."'";
        
        if(isset($this->master['HasilJahitId']))
        {
            $sql .= " and jthjd.transaksi_hasil_jahit_id='".$this->master['HasilJahitId']."'";
        }
        
        $sql .= " order by karyawan_nama asc ";
        
        if(!$prev && (empty($this->src) || is_array($this->src)))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "HasilJahitDetId" => $row['transaksi_hasil_jahit_detail_id'],
                    "NoDet" => $i,
                    "HasilJahitIdDet" => $row['transaksi_hasil_jahit_id'],
                    "KaryawanIdDet" => $row['karyawan_id'],
                    "PinIdDet" => $row['karyawan_pin'],
                    "KaryawanNameDet" => $row['karyawan_nama'],
                    "SizeIdDet" => $row['ukuran_id'],
                    "SizeDescDet" => $row['ukuran_desc'],
                    "JumlahJahitDet" => $row['transaksi_hasil_jahit_detail_jumlah'],
                    "PremiJahitDet" => $row['transaksi_hasil_jahit_detail_premi'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "HasilJahitDetId" => $row['transaksi_hasil_jahit_detail_id'],
                    "NoDet" => $i,
                    "HasilJahitIdDet" => $row['transaksi_hasil_jahit_id'],
                    "KaryawanIdDet" => $row['karyawan_id'],
                    "PinIdDet" => $row['karyawan_pin'],
                    "KaryawanNameDet" => $row['karyawan_nama'],
                    "SizeIdDet" => $row['ukuran_id'],
                    "SizeDescDet" => $row['ukuran_desc'],
                    "JumlahJahitDet" => $row['transaksi_hasil_jahit_detail_jumlah'],
                    "PremiJahitDet" => $row['transaksi_hasil_jahit_detail_premi'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            $i++;
            
        }    
        //print_r($ret);
        return $ret;
    }
    
    function get_items_det2($id="")
    {
        $ret = array();
                
        $sql = $this->sqlSearchDet;
        
        if(isset($id))
        {
            $sql .= " and jthjd.transaksi_hasil_jahit_id='".$id."'";
        }
        $res = mysql_query($sql);
        while($row = mysql_fetch_assoc($res))
        {   
            $ret[] = Array(
                "HasilJahitDetId" => $row['transaksi_hasil_jahit_detail_id'],
                "HasilJahitIdDet" => $row['transaksi_hasil_jahit_id'],
                "KaryawanIdDet" => $row['karyawan_id'],
                "PinIdDet" => $row['karyawan_pin'],
                "KaryawanNameDet" => $row['karyawan_nama'],
                "SizeIdDet" => $row['ukuran_id'],
                "SizeDescDet" => $row['ukuran_desc'],
                "JumlahJahitDet" => $row['transaksi_hasil_jahit_detail_jumlah'],
                "PremiJahitDet" => $row['transaksi_hasil_jahit_detail_premi'],
                "LastEditDet" => $row['modifiy_date']
            );
            
        }    
        //print_r($ret);
        return $ret;
    }
    
    function get_items_ukuran_det($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearchUkuranDet;
        
        if($prev==true)
            $sql .= " and jthjdu.transaksi_hasil_jahit_detail_ukuran_id";
//        
        if(isset($this->master['HasilJahitDetId']))
        {
            $sql .= " and jthjdu.transaksi_hasil_jahit_detail_id='".$this->master['HasilJahitDetId']."'";
        }
        
        if(!$prev && empty($this->src))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "HasilUkuranDetId" => $row['transaksi_hasil_jahit_detail_ukuran_id'],
                    "NoUkuranDet" => $i,
                    "HasilJahitDetIdUkuran" => $row['transaksi_hasil_jahit_detail_id'],
                    "DetailUkuranIdDetail" => $row['ukuran_detail_id'],
                    "DetailUkuranJumlahDetail" => $row['jumlah'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "HasilUkuranDetId" => $row['transaksi_hasil_jahit_detail_ukuran_id'],
                    "NoUkuranDet" => $i,
                    "HasilJahitDetIdUkuran" => $row['transaksi_hasil_jahit_detail_id'],
                    "DetailUkuranIdDetail" => $row['ukuran_detail_id'],
                    "DetailUkuranJumlahDetail" => $row['jumlah'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            $i++;
            
        }    
        //print_r($ret);
        return $ret;
    }
    
    function getTotalData()
    {
        return mysql_num_rows(mysql_query($this->sqlSearch.$this->additionalQuery));
    }
    
    function getTotalDataDet()
    {
        return mysql_num_rows(mysql_query($this->sqlSearchDet));
    }
    
    function getTotalDataUkuranDet()
    {
        return mysql_num_rows(mysql_query($this->sqlSearchUkuranDet));
    }
    
    function save_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $dtArr = explode("-", $this->master['HasilJahitDate']);
            $curBarcode = $this->jum_data($dtArr[2]."-".$dtArr[1]."-".$dtArr[0],
                                          $this->master['JenisId'],
                                          $this->master['IndexJamKerja'],
                                          $this->master['HangingId']);
            
            if($curBarcode==0)
            {
                
                
                $sql = "insert into ".$this->tblName." set 
                            transaksi_hasil_jahit_date = '".$dtArr[2]."-".$dtArr[1]."-".$dtArr[0]."',
                            jenis_id = '".$this->master['JenisId']."',
                            transaksi_hasil_jahit_jam_kerja = '".$this->master['IndexJamKerja']."',
                            hanging_id = '".$this->master['HangingId']."',
                            created_by = '".$_SESSION['UserId']."',
                            create_date = now(),
                            modified_by = '".$_SESSION['UserId']."',
                            modifiy_date = now()";
                $res = mysql_query($sql);
                if($res)
                {
                    $this->master['masId'] = mysql_insert_id();
                    
                    $karyawan = new karyawan();
                    $karyawan->src="hanging";
                    $karyawan->master['HangingId'] = $this->master['HangingId'];
                    
                    $dataKaryawan = $karyawan->get_items();
                    
                    for($i = 0 ; $i < count($dataKaryawan) ; $i++)
                    {
                        $this->master['karyawan_id'] = $dataKaryawan[$i]['KaryawanId'];
                        $this->save_items_det();
                    }
                    
                    $ret['res'] = "OK";
                    $ret['msg'] = ITEM_SUKSES_SIMPAN;
                }
                else
                {
                    $ret['res'] = "ERROR";
                    $ret['msg'] = ITEM_GAGAL_SIMPAN;
                }
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_BARCODE_ADA;
            }
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function save_items_det()
    {
        $ret = array();
        if(!empty($this->master))
        {
            
            if(isset($this->master['masId']))
                $transaksi_hasil_jahit_id = $this->master['masId'];
            else if(isset($this->master['HasilJahit']))
                $transaksi_hasil_jahit_id = $this->master['HasilJahit'];
            
            $sql = "insert into ".$this->tblNameDet." set 
                        transaksi_hasil_jahit_id = '".$transaksi_hasil_jahit_id."',
                        karyawan_id = '".$this->master['karyawan_id']."',
                        ukuran_id = '',
                        transaksi_hasil_jahit_detail_jumlah = '',
                        transaksi_hasil_jahit_detail_premi = '',
                        created_by = '".$_SESSION['UserId']."',
                        create_date = now(),
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()";
            $res = mysql_query($sql);
            if($res)
            {
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_SIMPAN;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_SIMPAN;
            }
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function save_ukuran_items_det()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $curBarcode = $this->jum_data_det_ukuran($this->master['HasilJahitDetIdUkuran'],
                                          $this->master['DetailUkuranIdDetail']);
            
            if($curBarcode==0)
            {
                
                
                $sql = "insert into ".$this->tblNameDetUkuran." set
                            transaksi_hasil_jahit_detail_id = '".$this->master['HasilJahitDetIdUkuran']."',
                            ukuran_detail_id = '".$this->master['DetailUkuranIdDetail']."',
                            jumlah = '".$this->master['DetailUkuranJumlahDetail']."',
                            created_by = '".$_SESSION['UserId']."',
                            create_date = now(),
                            modified_by = '".$_SESSION['UserId']."',
                            modifiy_date = now()";
                $res = mysql_query($sql);
                if($res)
                {                    
//                    $this->hitung_premi($this->master['HasilJahitDetIdUkuran']);
                    $ret['res'] = "OK";
                    $ret['msg'] = ITEM_SUKSES_SIMPAN;
                }
                else
                {
                    $ret['res'] = "ERROR";
                    $ret['msg'] = ITEM_GAGAL_SIMPAN;
                }
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_BARCODE_ADA;
            }
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function update_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $dtArr = explode("-", $this->master['HasilJahitDate']);
            $this->src = "hanging";
            $hj = $this->get_items();
            if($hj[0]['HangingId']!=$this->master['HangingId'])
            {
                $this->delete_items_det();
                
                $this->master['masId'] = $this->master['HasilJahitId'];
                    
                $karyawan = new karyawan();
                $karyawan->src="hanging";
                $karyawan->master['HangingId'] = $this->master['HangingId'];

                $dataKaryawan = $karyawan->get_items();
                for($i = 0 ; $i < count($dataKaryawan) ; $i++)
                {
                    $this->master['karyawan_id'] = $dataKaryawan[$i]['KaryawanId'];
                    $this->save_items_det();
                }
            }
            
            $sql = "update ".$this->tblName." set 
						transaksi_hasil_jahit_date = '".$dtArr[2]."-".$dtArr[1]."-".$dtArr[0]."',
                        jenis_id = '".$this->master['JenisId']."',
                        transaksi_hasil_jahit_jam_kerja = '".$this->master['IndexJamKerja']."',
                        hanging_id = '".$this->master['HangingId']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where transaksi_hasil_jahit_id = '".$this->master['HasilJahitId']."'";
            $res = mysql_query($sql);
            if($res)
            {
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_UPDATE;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_UPDATE;
            }
           
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function update_items_det()
    {
        $ret = array();
        if(!empty($this->master))
        {
            
            $sql = "update ".$this->tblName." set 
                        target_min = '".$this->master['TargetMin']."',
                        target_from = '".$this->master['TargetFrom']."',
                        target_until = '".$this->master['TargetUntil']."',
                        target_max = '".$this->master['TargetMax']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where detail_index_id = '".$this->master['IndexDetId']."'";
            $res = mysql_query($sql);
            if($res)
            {
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_UPDATE;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_UPDATE;
            }
           
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function update_items_ukuran_det()
    {
        $ret = array();
        if(!empty($this->master))
        {
            
            $sql = "update ".$this->tblNameDetUkuran." set 
                        ukuran_detail_id = '".$this->master['DetailUkuranIdDetail']."',
                        jumlah = '".$this->master['DetailUkuranJumlahDetail']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where transaksi_hasil_jahit_detail_ukuran_id = '".$this->master['HasilUkuranDetId']."'";
            $res = mysql_query($sql);
            if($res)
            {
//                $this->hitung_premi($this->master['HasilJahitDetIdUkuran']);
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_UPDATE;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_UPDATE;
            }
           
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function delete_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $sql = "delete from ".$this->tblName." where index_id = '".$this->master['IndexId']."'";
            $res = mysql_query($sql);
            if($res)
            {
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_HAPUS;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_HAPUS;
            }
           
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function delete_items_det()
    {
        $ret = array();
        if(!empty($this->master))
        {
            //$sql = "delete from ".$this->tblNameDet." where transaksi_hasil_jahit_detail_id = '".$this->master['HasilJahitDetailId']."'";
            
            if(isset($this->master['HasilJahitDetId']))
            {
                $sql = "delete from ".$this->tblNameDet." where transaksi_hasil_jahit_detail_id = '".$this->master['HasilJahitDetId']."'";
            }
            
            
            $res = mysql_query($sql);
            if($res)
            {
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_HAPUS;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_HAPUS;
            }
           
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function delete_items_ukuran_det()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $sql = "delete from ".$this->tblNameDetUkuran." where transaksi_hasil_jahit_detail_ukuran_id = '".$this->master['HasilUkuranDetId']."'";
            
//            if(isset($this->master['HasilJahitId']))
//            {
//                $sql = "delete from ".$this->tblNameDet." where transaksi_hasil_jahit_id = '".$this->master['HasilJahitId']."'";
//            }
            
            
            $res = mysql_query($sql);
            if($res)
            {
//                $this->hitung_premi($this->master['HasilJahitDetIdUkuran']);
                $ret['res'] = "OK";
                $ret['msg'] = ITEM_SUKSES_HAPUS;
            }
            else
            {
                $ret['res'] = "ERROR";
                $ret['msg'] = ITEM_GAGAL_HAPUS;
            }
           
        }
        else
        {
            $ret['res'] = "ERROR";
            $ret['msg'] = ITEM_FORM_KOSONG;
        }
        
        return $ret;
    }
    
    function jum_data($param1,$param2,$param3,$param4)
    {
        $sql = $this->sqlSearch;
        $sql .= " and a.transaksi_hasil_jahit_date='".$param1."'
                  and a.jenis_id='".$param2."'
                  and a.transaksi_hasil_jahit_jam_kerja='".$param3."'
                  and a.hanging_id='".$param4."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
    function jum_data_det($param1,$param2)
    {
        $sql = $this->sqlSearchDet;
        $sql .= " and b.detail_index_id='".$param1."' and b.size_id = '".$param2."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
    
    function jum_data_det_ukuran($param1,$param2)
    {
        $sql = $this->sqlSearchUkuranDet;
        $sql .= " and jthjdu.transaksi_hasil_jahit_detail_id='".$param1."' and jthjdu.ukuran_detail_id = '".$param2."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
    
    function hitung_premi($param)
    {
        //$ret = array();
        $first = true;
        $ukuran = 0;
        $tgt_min = 0;
        $pcs = 0;
        $jenis = 0;
        $hasil_jahit_id = 0;
        $premi = 0;
        $persen = 0;
        
        $sql = "select a.transaksi_hasil_jahit_id,
                       a.jenis_id,
                       a.transaksi_hasil_jahit_jam_kerja jam_kerja,
                       a.hanging_id,
                       c1.ukuran_id,
                       sum(c.jumlah) jumlah
                from jht_transaksi_hasil_jahit a,
                     jht_transaksi_hasil_jahit_detail b,
                     jht_transaksi_hasil_jahit_detail_ukuran c
                        left join jht_master_ukuran_detail c1 on c.ukuran_detail_id = c1.ukuran_detail_id
                where a.transaksi_hasil_jahit_id = b.transaksi_hasil_jahit_id
                      and b.transaksi_hasil_jahit_detail_id = c.transaksi_hasil_jahit_detail_id
                      and b.transaksi_hasil_jahit_detail_id = '".$param."'
                group by ukuran_id
                order by jumlah desc";
        $res = mysql_query($sql);
        $first = true;
        while ($row = mysql_fetch_assoc($res))
        {           
            $sql1 = "select a.index_id,
                            a.index_target,
                            a.jenis_id,
                            a.index_jam_kerja,
                            a.index_incentif,
                            b.size_id,
                            b.target_min,
                            b.target_from,
                            b.target_until,
                            b.target_max
                    from jht_index_jahit a,
                         jht_index_jahit_detail b
                    where a.index_id = b.index_id
                          and a.jenis_id = '".$row['jenis_id']."'
                          and a.index_jam_kerja = '".$row['jam_kerja']."'
                          and b.size_id = '".$row['ukuran_id']."'";
            $res1 = mysql_query($sql1);
            $row1 = mysql_fetch_assoc($res1);
            
            if($first)
            {
                $ukuran = $row['ukuran_id'];
                $tgt_min = $row1['target_min'];
                $jenis = $row['jenis_id'];
                $hasil_jahit_id = $row['transaksi_hasil_jahit_id'];
            }
            $first = false;
            if($row['jumlah']!=0 && $row1['target_min']!=0)
                $persen += round($row['jumlah']/$row1['target_min'],4);         
            
        }
        
        $pcs = $tgt_min+(round($tgt_min*($persen-1)));
        
        $sqlPremi = "select a.transaksi_hasil_jahit_id,
                            b.index_target,
                            b.target_min,
                            b.target_from,
                            b.target_until,
                            b.target_max,
                            sum(if(($pcs >= b.target_from and $pcs >= b.target_until) and b.target_from<>0,
                                    (b.target_until - (b.target_from-1)) * b.index_incentif,
                                     if($pcs >=b.target_max and b.target_max<>0,
                                            ($pcs - (b.target_max-1)) * b.index_incentif,
                                            ($pcs - (b.target_from-1)) * b.index_incentif))) premi
                    from jht_transaksi_hasil_jahit a,
                              jht_index_jahit_view b
                    where a.transaksi_hasil_jahit_jam_kerja = b.index_jam_kerja
                                    and b.size_id = '".$ukuran."'
                                    and b.jenis_id = '".$jenis."'
                                    and a.transaksi_hasil_jahit_id = '".$hasil_jahit_id."'
                                    and (
                                            (
                                                $pcs >= b.target_from and $pcs <= b.target_until) 
                                                or ($pcs >= b.target_max and b.target_max <> 0)   
                                                or ($pcs > b.target_from and b.target_from <> 0)
                                            ) group by a.transaksi_hasil_jahit_id";
        $resPremi = mysql_query($sqlPremi);
        $rowPremi = mysql_fetch_assoc($resPremi);
        
        if($rowPremi['premi'])
            $premi = $rowPremi['premi'];
        //echo $premi."<br>";
        $sqlU = "update ".$this->tblNameDet." 
                 set ukuran_id = '".$ukuran."',
                     transaksi_hasil_jahit_detail_jumlah = '".$pcs."',
                     transaksi_hasil_jahit_detail_premi = '".$premi."',
                     transaksi_hasil_jahit_detail_persen = '".$persen."'
                where transaksi_hasil_jahit_detail_id = '".$param."'";
        $resU = mysql_query($sqlU);
        
        //print_r($ret);
    }
	
	function hitung_premi_calc()
    {
        //$ret = array();
        $first = true;
        $ukuran = 0;
        $ukuranDesc = "";
        $tgt_min = 0;
        $pcs = 0;
        $jenis = 0;
        $hasil_jahit_id = 0;
        $premi = 0;
        $persen = 0;
        
        $arrUkuran = array();
        
        foreach($this->master['ukuran'] as $k => $v)
        {
            $sql = "select a.ukuran_id from jht_master_ukuran_detail a where a.ukuran_detail_id='$k'";
            $res = mysql_query($sql);
            $row = mysql_fetch_assoc($res);
            
            if(!isset($arrUkuran[$row['ukuran_id']]))
            {
                $arrUkuran[$row['ukuran_id']] = $v;
            }
            else
            {
                $arrUkuran[$row['ukuran_id']] += $v;
            }
            
            arsort($arrUkuran);
        }
        
        $first = true;
        foreach ($arrUkuran as $k => $v)
        {           
            $sql1 = "select a.index_id,
                            a.index_target,
                            a.jenis_id,
                            a.index_jam_kerja,
                            a.index_incentif,
                            b.size_id,
                            b.target_min,
                            b.target_from,
                            b.target_until,
                            b.target_max,
                            c.ukuran_desc
                    from jht_index_jahit a,
                         jht_index_jahit_detail b left join jht_master_ukuran c 
                            on b.size_id = c.ukuran_id
                    where a.index_id = b.index_id
                          and a.jenis_id = '".$this->master['jenis']."'
                          and a.index_jam_kerja = '".$this->master['jam']."'
                          and b.size_id = '".$k."'";
            $res1 = mysql_query($sql1);
            $row1 = mysql_fetch_assoc($res1);
            
            if($first)
            {
                $ukuran = $k;
                $ukuranDesc = $row1['ukuran_desc'];
                $tgt_min = $row1['target_min'];
                $jenis = $this->master['jenis'];
                //$hasil_jahit_id = $row['transaksi_hasil_jahit_id'];
            }
            $first = false;
            if($v!=0 && $row1['target_min']!=0)
                $persen += round($v/$row1['target_min'],4);         
            
        }
        $pc = round($tgt_min*($persen-1));
        $pcs = $tgt_min+(round($tgt_min*($persen-1)));
        
        echo $sqlPremi = "select b.index_target,
                            b.target_min,
                            b.target_from,
                            b.target_until,
                            b.target_max,
                            sum(if(($pcs >= b.target_from and $pcs >= b.target_until) and b.target_from<>0,
                                    (b.target_until - (b.target_from-1)) * b.index_incentif,
                                     if($pcs >=b.target_max and b.target_max<>0,
                                            ($pcs - (b.target_max-1)) * b.index_incentif,
                                            ($pcs - (b.target_from-1)) * b.index_incentif))) premi
                    from jht_index_jahit_detail b
                    where b.index_jam_kerja = '".$this->master['jam']."'
                                    and b.size_id = '".$ukuran."'
                                    and b.jenis_id = '".$jenis."'
                                    and (
                                            (
                                                $pcs >= b.target_from and $pcs <= b.target_until) 
                                                or ($pcs >= b.target_max and b.target_max <> 0)   
                                                or ($pcs > b.target_from and b.target_from <> 0)
                                            ) group by b.index_jam_kerja";
        $resPremi = mysql_query($sqlPremi);
        $rowPremi = mysql_fetch_assoc($resPremi);
        
        if($rowPremi['premi'])
            $premi = $rowPremi['premi'];
        
        return array("ukuran"=>$ukuranDesc,
                     "persen"=>(($persen*100)-100),
                     "pcs"=>$pc,
                     "minpcs"=>$pcs,
                     "premi"=>number_format($premi));
    }
}

?>