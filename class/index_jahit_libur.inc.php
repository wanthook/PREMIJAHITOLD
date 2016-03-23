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
class index_jahit_libur 
{
    //Parameter Master
    public $master;
    
    //for searching
    public $start_rows;
    public $size_rows;
    public $order_by;
    public $src;
    
    private $sqlSearch,$sqlSearchDet;
    private $tblName,$tblNameDet;
    
    function __construct() 
    {
        
        $this->tblName = "jht_index_jahit_libur";
        $this->tblNameDet = "jht_index_jahit_libur_detail";
        $this->sqlSearch = "select * from ".$this->tblName." where 1=1 ";
        $this->sqlSearchDet = "select b.detail_index_id,
                        b.index_id,
                        b.size_id,
                        b.target_min,
                        b.target_from,
                        b.target_until,
                        b.target_max,
                        b.premi,
                        b.created_by,
                        b.create_date,
                        b.modified_by,
                        b.modifiy_date
               from ".$this->tblName." a,
                         ".$this->tblNameDet." b
               where a.index_id = b.index_id";
    }
    
    function get_items($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearch;
        
        if($prev==true)
            $sql .= " and index_id=LAST_INSERT_ID()";
        
        if(!$prev && (empty($this->src) || is_array($this->src)))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "IndexId" => $row['index_id'],
                    "No" => $i,
                    "IndexTarget" => $row['index_target'],
                    "JenisId" => $row['jenis_id'],
                    "IndexJamKerja" => $row['index_jam_kerja'],
                    "IndexIncentif" => $row['index_incentif'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "IndexId" => $row['index_id'],
                    "No" => $i,
                    "IndexTarget" => $row['index_target'],
                    "JenisId" => $row['jenis_id'],
                    "IndexJamKerja" => $row['index_jam_kerja'],
                    "IndexIncentif" => $row['index_incentif'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            $i++;
            
        }    
        //print_r($ret);
        return $ret;
    }
    
    function get_items_det($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearchDet;
        
        if($prev==true)
            $sql .= " and b.detail_index_id=LAST_INSERT_ID()";
        
        if(isset($this->master['IndexIdDet']))
        {
            $sql .= " and b.index_id='".$this->master['IndexIdDet']."'";
        }
        
        if(!$prev && (empty($this->src) || is_array($this->src)))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "IndexDetId" => $row['detail_index_id'],
                    "NoDet" => $i,
                    "IndexIdDet" => $row['index_id'],
                    "SizeId" => $row['size_id'],
                    "TargetMin" => $row['target_min'],
                    "TargetFrom" => $row['target_from'],
                    "TargetUntil" => $row['target_until'],
                    "TargetMax" => $row['target_max'],
                    "Premi" => $row['premi'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "IndexDetId" => $row['detail_index_id'],
                    "NoDet" => $i,
                    "IndexIdDet" => $row['index_id'],
                    "SizeId" => $row['size_id'],
                    "TargetMin" => $row['target_min'],
                    "TargetFrom" => $row['target_from'],
                    "TargetUntil" => $row['target_until'],
                    "TargetMax" => $row['target_max'],
                    "Premi" => $row['premi'],
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
        return mysql_num_rows(mysql_query($this->sqlSearch));
    }
    
    function getTotalDataDet()
    {
        $sql = $this->sqlSearchDet;
        if(isset($this->master['IndexIdDet']))
        {
            $sql .= " and b.index_id='".$this->master['IndexIdDet']."'";
        }
        return mysql_num_rows(mysql_query($sql));
    }
    
    function save_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $curBarcode = $this->jum_data($this->master['IndexTarget'],
                                         $this->master['JenisId'],
                                         $this->master['IndexJamKerja']);
            
            if($curBarcode==0)
            {
                $sql = "insert into ".$this->tblName." set 
                            index_target = '".$this->master['IndexTarget']."',
                            jenis_id = '".$this->master['JenisId']."',
                            index_jam_kerja = '".$this->master['IndexJamKerja']."',
                            index_incentif = '".$this->master['IndexIncentif']."',
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
            $curBarcode = $this->jum_data_det($this->master['IndexIdDet'],$this->master['SizeId']);
            
            if($curBarcode==0)
            {

                $sql = "insert into ".$this->tblNameDet." set 
                            index_id = '".$this->master['IndexIdDet']."',
                            size_id = '".$this->master['SizeId']."',
                            target_min = '".$this->master['TargetMin']."',
                            target_from = '".$this->master['TargetFrom']."',
                            target_until = '".$this->master['TargetUntil']."',
                            target_max = '".$this->master['TargetMax']."',
                            premi = '".$this->master['Premi']."',
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
            
            $sql = "update ".$this->tblName." set 
                        jenis_id = '".$this->master['JenisId']."',
                        index_jam_kerja = '".$this->master['IndexJamKerja']."',
                        index_incentif = '".$this->master['IndexIncentif']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where index_id = '".$this->master['IndexId']."'";
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
            
            $sql = "update ".$this->tblNameDet." set 
                        target_min = '".$this->master['TargetMin']."',
                        target_from = '".$this->master['TargetFrom']."',
                        target_until = '".$this->master['TargetUntil']."',
                        target_max = '".$this->master['TargetMax']."',
                        premi = '".$this->master['Premi']."',
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
            
            $sql = "delete from ".$this->tblNameDet." where detail_index_id = '".$this->master['IndexDetId']."'";
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
    
    function jum_data($param1,$param2,$param3)
    {
        $sql = $this->sqlSearch;
        $sql .= " and index_target = '".$param1."'
                  and jenis_id = '".$param2."'
                  and index_jam_kerja = '".$param3."' ";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
    function jum_data_det($param1,$param2)
    {
        $sql = $this->sqlSearchDet;
        $sql .= " and b.index_id='".$param1."' and b.size_id = '".$param2."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
}

?>
