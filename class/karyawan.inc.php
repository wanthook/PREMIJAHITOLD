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
class karyawan 
{
    //Parameter Master
    public $master;
    
    //for searching
    public $start_rows;
    public $size_rows;
    public $order_by;
    public $src;
    
    private $sqlSearch;
    private $tblName;
    
    function __construct() 
    {
        
        $this->tblName = "jht_master_karyawan";
        $this->sqlSearch = "select * from ".$this->tblName." where 1=1";
    }
    
    function get_items($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearch;
        
        if($prev==true)
            $sql .= " and karyawan_id=LAST_INSERT_ID()";
        
        if(isset($this->master['HangingId']))
        {
            $sql .= " and hanging_id='".$this->master['HangingId']."'";
        }
        
        $sql .= " order by hanging_id, karyawan_nama, modifiy_date desc";
        if(!$prev && (empty($this->src) || is_array($this->src)))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "KaryawanId" => $row['karyawan_id'],
                    "PinId" => $row['karyawan_pin'],
                    "No" => $i,
                    "KaryawanName" => $row['karyawan_nama'],
                    "HangingId" => $row['hanging_id'],
                    "Flag" => $row['flag'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "KaryawanId" => $row['karyawan_id'],
                    "PinId" => $row['karyawan_pin'],
                    "No" => $i,
                    "KaryawanName" => $row['karyawan_nama'],
                    "HangingId" => $row['hanging_id'],
                    "Flag" => $row['flag'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            $i++;
            
        }        
        return $ret;
    }
    
    function get_items_search()
    {
        $ret = null;
                
        $sql = "select a.*
                from jht_master_karyawan a
                where a.karyawan_id not in 
                (
                        select a2.karyawan_id 
                        from  jht_transaksi_hasil_jahit a1,
                                        jht_transaksi_hasil_jahit_detail a2
                        where a1.transaksi_hasil_jahit_id = '".$this->master['HasilJahitId']."'
							  and a1.transaksi_hasil_jahit_id = a2.transaksi_hasil_jahit_id
                )
                and a.hanging_id = '".$this->master['HangingId']."'";
        
       
        $sql .= " order by karyawan_nama asc";
        
        $res = mysql_query($sql);
        while($row = mysql_fetch_assoc($res))
        {   
            
            $ret[] = Array(
                "KaryawanId" => $row['karyawan_id'],
                "PinId" => $row['karyawan_pin'],
                "KaryawanName" => $row['karyawan_nama'],
                "HangingId" => $row['hanging_id'],
                "Flag" => $row['flag'],
                "LastEdit" => $row['modifiy_date']
            );
        }        
        return $ret;
    }
    
    function getTotalData()
    {
        return mysql_num_rows(mysql_query($this->sqlSearch));
    }
    
    function save_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $curBarcode = $this->jum_data($this->master['KaryawanId']);
            
            if($curBarcode==0)
            {
                $sql = "insert into ".$this->tblName." set 
                            karyawan_pin = '".$this->master['PinId']."',
                            karyawan_nama = '".$this->master['KaryawanName']."',
                            hanging_id = '".$this->master['HangingId']."',
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
    
    function update_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            
            $sql = "update ".$this->tblName." set 
                        karyawan_nama = '".$this->master['KaryawanName']."',
                        hanging_id = '".$this->master['HangingId']."',
                        flag = '".$this->master['Flag']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where karyawan_id = '".$this->master['KaryawanId']."'";
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
            
            $sql = "delete from ".$this->tblName." where karyawan_id = '".$this->master['KaryawanId']."'";
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
        $sql .= " and karyawan_id='".$param."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
}

?>
