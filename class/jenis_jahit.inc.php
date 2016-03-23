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
class jenis_jahit 
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
        
        $this->tblName = "jht_master_jenis_jahit";
        $this->sqlSearch = "select * from ".$this->tblName." where 1=1 ";
    }
    
    function get_items($prev=false)
    {
        $ret = null;
                
        $sql = $this->sqlSearch;
        
        if($prev==true)
            $sql .= " and jenis_id=LAST_INSERT_ID()";
        
        if($this->src=="act")
            $sql .= " and flag='1' ";
        
        if(!$prev && empty($this->src))
            $sql .= " limit ".$this->start_rows.", ".$this->size_rows;
        
        $res = mysql_query($sql);
        $i=  intval($this->start_rows)+1;
        while($row = mysql_fetch_assoc($res))
        {   
            if(!$prev)
            {
                $ret[] = Array(
                    "JenisId" => $row['jenis_id'],
                    "No" => $i,
                    "JenisDescription" => $row['jenis_desc'],
                    "Flag" => $row['flag'],
                    "LastEdit" => $row['modifiy_date']
                );
            }
            else
            {
                $ret = Array(
                    "JenisId" => $row['jenis_id'],
                    "No" => $i,
                    "JenisDescription" => $row['jenis_desc'],
                    "Flag" => $row['flag'],
                    "LastEdit" => $row['modifiy_date']
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
    
    function save_items()
    {
        $ret = array();
        if(!empty($this->master))
        {
            $curBarcode = $this->jum_data($this->master['JenisId']);
            
            if($curBarcode==0)
            {
                $sql = "insert into ".$this->tblName." set 
                            jenis_desc = '".$this->master['JenisDescription']."',
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
                        jenis_desc = '".$this->master['Flag']."',
                        modified_by = '".$_SESSION['UserId']."',
                        modifiy_date = now()
                    where jenis_id = '".$this->master['JenisId']."'";
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
            
            $sql = "delete from ".$this->tblName." where jenis_id = '".$this->master['JenisId']."'";
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
        $sql .= " and jenis_id='".$param."'";
        $res = mysql_query($sql);
        $jum = mysql_num_rows($res);
        
        return $jum;
    }
}

?>
