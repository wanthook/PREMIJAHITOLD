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
class ukuran 
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
        
        $this->tblName = "jht_master_ukuran";
        $this->tblNameDet = "jht_master_ukuran_detail";
        $this->sqlSearch = "select * from ".$this->tblName." where 1=1";
        $this->sqlSearchDet = "select * from ".$this->tblNameDet." where 1=1";
    }
    
    function get_items($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearch;
        
        if($prev==true)
            $sql .= " and ukuran_id=LAST_INSERT_ID()";
        
        if(!$prev && empty($this->src))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "UkuranId" => $row['ukuran_id'],
                    "No" => $i,
                    "UkuranDescription" => $row['ukuran_desc'],
                    "Flag" => $row['flag'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "UkuranId" => $row['ukuran_id'],
                    "No" => $i,
                    "UkuranDescription" => $row['ukuran_desc'],
                    "Flag" => $row['flag'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            $i++;
            
        }        
        return $ret;
    }
    
    function get_items_det($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearchDet;
        
        if($prev==true && empty($this->src))
            $sql .= " and ukuran_detail_id=LAST_INSERT_ID()";
        
        if(isset($this->master['UkuranIdDet']))
        {
            $sql .= " and ukuran_id='".$this->master['UkuranIdDet']."'";
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
                    "ItemDetId" => $row['ukuran_detail_id'],
                    "NoDet" => $i,
                    "UkuranIdDet" => $row['ukuran_id'],
                    "UkuranDetailCode" => $row['ukuran_detail_code'],
                    "UkuranDetailDescription" => $row['ukuran_detail_desc'],
                    "FlagDet" => $row['flag'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "ItemDetId" => $row['ukuran_detail_id'],
                    "NoDet" => $i,
                    "UkuranIdDet" => $row['ukuran_id'],
                    "UkuranDetailCode" => $row['ukuran_detail_code'],
                    "UkuranDetailDescription" => $row['ukuran_detail_desc'],
                    "FlagDet" => $row['flag'],
                    "LastEditDet" => $row['modifiy_date']
                );
            }
            $i++;
            
        }        
        return $ret;
    }
    
    function getTotalData()
    {
        return mysql_num_rows(mysql_query($this->sqlSearch));
    }
    
    function getTotalDataDet()
    {
        return mysql_num_rows(mysql_query($this->sqlSearchDet));
    }
    
    function save_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $curBarcode = $this->jum_data($this->master['UkuranDescription']);
            
            if($curBarcode==0)
            {
                $sql = "insert into ".$this->tblName." set                             
                            ukuran_desc = '".$this->master['UkuranDescription']."',
                            flag = '".$this->master['Flag']."',
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
            $curBarcode = $this->jum_data_det($this->master['UkuranDetailDescription']);
            
            if($curBarcode==0)
            {
                $sql = "insert into ".$this->tblNameDet." set 
                            ukuran_id = '".$this->master['UkuranIdDet']."',
                            ukuran_detail_code = '".$this->master['UkuranDetailCode']."',
                            ukuran_detail_desc = '".$this->master['UkuranDetailDescription']."',
                            flag = '".$this->master['FlagDet']."',
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
                        ukuran_desc = '".$this->master['UkuranDescription']."',
                        flag = '".$this->master['Flag']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where ukuran_id = '".$this->master['UkuranId']."'";
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
                        ukuran_detail_code = '".$this->master['UkuranDetailCode']."',
                        flag = '".$this->master['FlagDet']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where ukuran_detail_id = '".$this->master['ItemDetId']."'";
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
            
            $sql = "delete from ".$this->tblName." where ukuran_id = '".$this->master['UkuranId']."'";
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
            
            $sql = "delete from ".$this->tblNameDet." where ukuran_detail_id = '".$this->master['ItemDetId']."'";
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
    
    function jum_data($param)
    {
        $sql = $this->sqlSearch;
        $sql .= " and ukuran_desc='".$param."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
    
    function jum_data_det($param)
    {
        $sql = $this->sqlSearchDet;
        $sql .= " and ukuran_detail_desc='".$param."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
}

?>
