<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of globalfunc
 *
 * @author taufiq
 */
class globalfunc 
{
    //put your code here
    function include_style($theme="smoothness")
    {
        ?>
        <link href="style/<?=$theme?>/jquery-ui.css" rel="stylesheet" type="text/css" />  
        <link href="js/jtable/themes/jqueryui/jtable_jqueryui.css" rel="stylesheet" type="text/css" />  
        <link href="style/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
        <link href="style/terry.css" rel="stylesheet" type="text/css" />
        <?
    }
    
    function include_js()
    {
        ?>
        <script src="js/jquery-1.9.1.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/jtable/jquery.jtable.js" type="text/javascript"></script>
        <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
        <script src="js/validation_lang/jquery.validationEngine-en.js" type="text/javascript"></script>
        <script src="js/jquery.fileDownload.js" type="text/javascript"></script>
        <script src="js/global.js"></script>
        <?
    }
    
    function alert_ui($msg="")
    {
        ?>
        <div class="ui-widget">
            <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                <strong>Alert:</strong> <?=$msg?></p>
            </div>
        </div>
        <?
    }
    
    function get_param($parStr,$withEscape=true)
    {
        $getPar = null;
        
        if(isset($_REQUEST[$parStr]))
        {
            $getPar = $_REQUEST[$parStr];
            
            if($withEscape)
                return mysql_real_escape_string ($getPar);
            else
                return $getPar;
        }
        
        return false;
    }
    
    function get_menu()
    {
        $ret = null;
        if(isset($_SESSION['UserId']))
        {
            $sqlH = "select a.*
                    from tp_menu a,
                              tp_user_modul b
                    where a.ModulHead = 0
                          and a.ModulFlag = 1
                          and a.ModulId = b.ModulId
                          and b.UserId = '".$_SESSION['UserId']."'
                    order by ModulOrder asc";
            $resH = @mysql_query($sqlH);
            while($rowH = @mysql_fetch_assoc($resH))
            {
                $child = null;
                
                $sqlM = "select a.*
                    from tp_menu a,
                              tp_user_modul b
                    where a.ModulHead = '".$rowH['ModulId']."'
                          and a.ModulFlag = 1
                          and a.ModulId = b.ModulId
                          and b.UserId = '".$_SESSION['UserId']."'
                    order by ModulOrder asc";
                
                $resM = @mysql_query($sqlM);
                while($rowM = @mysql_fetch_assoc($resM))
                {
                    $child[] = array("name"=>$rowM['ModulName'],
                                     "target"=>$rowM['ModulTarget']);
                }
                $ret[] = array("name"=>$rowH['ModulName'],
                               "target"=>$rowH['ModulTarget'],
                               "child"=>$child);
            }
            
            return $ret;
        }
        
        return false;
    }
    
    function create_menu_html()
    {
        $arr = $this->get_menu();
        
        foreach ($arr as $key => $value)
        {
            ?>
                <h3><?=$value['name']?></h3>
                <div>
                    <?
                    foreach ($value['child'] as $k => $v)
                    {
                        ?>
                        <a class="menuUtama" href="<?=$v['target']?>"><?=$v['name']?></a><br>
                        <?
                    }
                    ?>
                </div>
            <?
        }
    }
    
    function getSpellDate($param)
    {
        $split = explode("-", $param);
        
        $lArr = count($split);
        switch($lArr)
        {
            case 2:
                return $this->monthInd($split[1])." ".$split[0];
            break;
            case 3:
                return $split[2]." ".$this->monthInd($split[1])." ".$split[0];
            break;
        }
    }
    
    function monthInd($param)
    {
        $ret = "";
        switch (intval($param))
        {
            case 1: 
                $ret = "Januari";
            break;
            case 2: 
                $ret = "Pebruari";
            break;
            case 3: 
                $ret = "Maret";
            break;
            case 4: 
                $ret = "April";
            break;
            case 5: 
                $ret = "Mei";
            break;
            case 6: 
                $ret = "Juni";
            break;
            case 7: 
                $ret = "Juli";
            break;
            case 8: 
                $ret = "Agustus";
            break;
            case 9: 
                $ret = "September";
            break;
            case 10: 
                $ret = "Oktober";
            break;
            case 11: 
                $ret = "Nopember";
            break;
            case 12: 
                $ret = "Desember";
            break;
        }
        
        return $ret;
    }
    
    function get_app_ref($id,$code)
    {
        $ret = array();
        
        if(!empty($id) && !empty($code))
        {
            $sql = "select a.ref_value,
                           a.ref_description
                   from tp_application_reference a
                   where a.ref_id = '".$id."'
                         and a.ref_code = '".$code."'";
            $res = mysql_query($sql);
            while($row = mysql_fetch_assoc($res))
            {
                $ret[] = $row;
            }
        }
        
        return $ret;
    }
}
?>