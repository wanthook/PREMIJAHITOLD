/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */
var premiView = true;
var MYLIBRARY = MYLIBRARY || (function(){
    var _args = {}; // private

    return {
        init : function(Args) {
            _args = Args;
            // some other initialising
        },
        helloWorld : function() {
            if(_args[0]=="O")
                premiView = false;
        }
    };
}());
$(document).ready(function(){
    
    if($('#itemTableHasilJahit').jtable())
        $('#itemTableHasilJahit').jtable('destroy');
    
	//$("#cmbJamKerja").combobox();
	
    $("#cmdSearch")
        .button(
        {
            icons: 
            {
                primary: "ui-icon-search"
            }
        })
        .click(function(e)
        {
            e.preventDefault();
            var dt = $("#txtDatePeriode").val();
            var jj = $("#cmbJenisJahit").val();
            var jk = $("#cmbJamKerja").val();
            var hg = $("#cmbHanging").val();
            
            $('#itemTableHasilJahit').jtable('load',{
                SdatePeriode:dt,
                SjenisJahit:jj,
                SjamKerja:jk,
                SHanging:hg
            });
        });
	
    $('#txtDatePeriode').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
		beforeShow: function(input, inst)
		{
			$("#hideMonth").html('.ui-datepicker-calendar{display:none;}');
		},
		//maxDate: new Date(),
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
			setTimeout(function(){$('#hideMonth').html('');},300);
        }
    });
	
	//$('#txtDatePeriode').css('display: none');
	
	$('#itemTableHasilJahit').jtable(
    {
        title: 'Transaksi Hasil Jahit',
        paging: true, //Enable paging
        sorting: false, //Enable sorting
        columnResizable: false, //Disable column resizing
        columnSelectable: false, //Disable column selecting
        jqueryuiTheme: true,
        pageSize: 10,
		openChildAsAccordion: true,

        actions: 
        {
            listAction: './controller/con_hasil_jahit.php?action=view',
            createAction: './controller/con_hasil_jahit.php?action=create',
            updateAction: './controller/con_hasil_jahit.php?action=update'
            //deleteAction: './controller/con_hanging.php?action=delete'
        },
        fields: 
        {
            HasilJahitId: 
            {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            No:
            {
                title: 'No.',
                create: false,
                edit: false,
                width:"5%",
                sorting: false
            },
            HasilJahitDate: 
            {
                title: 'Tanggal',
                type: 'date',
                displayFormat: 'dd-mm-yy',
                edit: true
            },
            JenisId: 
            {
                title: 'Jenis Jahit',
                options: './controller/con_hasil_jahit.php?action=jen',
                inputClass: 'validate[required]'
            },
            IndexJamKerja: 
            {
                title: 'Jam Kerja',
                options: './controller/con_hasil_jahit.php?action=jamker',
                inputClass: 'validate[required]'
            },
            HangingId: 
            {
                title: 'Hanging',
                options: './controller/con_hasil_jahit.php?action=hang',
                inputClass: 'validate[required]'
            },
            LastEdit: 
            {
                title: 'Edit Time',
                type: 'date',
                displayFormat: 'dd-mm-yy',
                create: false,
                edit: false
            },
            ItemsDetail:
            {
                title: '',
                width: '1%',
                sorting: false,
                edit: false,
                create: false,
                display: function(item)
                {
                    var $imga = $('<img src="./images/list_metro.png" title="Edit Items Detail" />');
                    $imga.click(function () 
                    {
                        $('#itemTableHasilJahit').jtable('openChildTable',
                                $imga.closest('tr'), //Parent row
                                {
                                    title: 'Daftar Detail Jahit Tanggal '+item.record.HasilJahitDate,
                                    paging: true, //Enable paging
                                    sorting: false, //Enable sorting
                                    columnResizable: false, //Disable column resizing
                                    columnSelectable: false, //Disable column selecting
                                    jqueryuiTheme: true,
                                    pageSize: 10,

                                    actions: 
                                    {
                                        listAction: './controller/con_hasil_jahit.php?action=viewDet&HasilJahitId='+item.record.HasilJahitId,
                                        createAction: './controller/con_hasil_jahit.php?action=createDet&HasilJahitId='+item.record.HasilJahitId+'&HangingId='+item.record.HangingId,
                                        //updateAction: './controller/con_hasil_jahit.php?action=updateDet',
                                        deleteAction: './controller/con_hasil_jahit.php?action=deleteDet'
                                    },
                                    fields: 
                                    {
                                        HasilJahitDetId: 
                                        {
                                            key: true,
                                            create: false,
                                            edit: false,
                                            list: false
                                        },
                                        NoDet:
                                        {
                                            title: 'No.',
                                            create: false,
                                            edit: false,
                                            width:"5%",
                                            sorting: false
                                        },
                                        HasilJahitIdDet: 
                                        {
                                            type: 'hidden',
                                            edit: false,
                                            create: false,
                                            defaultValue: item.record.HasilJahitId
                                        },
                                        KaryawanIdDet: 
                                        {
                                            title: '',
                                            type: 'hidden',
                                            list: false
                                        },
                                        PinIdDet: 
                                        {
                                            title: 'PIN',
                                            //options: './controller/con_index_jahit.php?action=opt',
                                            input: function (data) 
                                            {
                                                var val = "";
                                                if(data.record)
                                                    val = data.record.PinIdDet;
                                                return '<input type="text" id="PinIdDet" name="PinIdDet" value="' + val + '" class="validate[required]" readonly />&nbsp;'+
                                                        '<input type="button" id="cmdSearchKaryawan" value="Search" style="height:30px;width:100px" />';
                                            }
                                        },                                        
                                        KaryawanNameDet: 
                                        {
                                            title: 'Nama Karyawan',
                                            input: function (data) 
                                            {
                                                var val = "";
                                                if(data.record)
                                                    val = data.record.KaryawanNameDet;
                                                return '<input type="text" id="KaryawanNameDet" name="KaryawanNameDet" value="' + val + '" class="validate[required]" readonly />';
                                            }
                                            //options: './controller/con_index_jahit.php?action=opt',
                                            //inputClass: 'validate[required]'
                                        },
                                        SizeIdDet: 
                                        {
                                            title: '',
                                            edit: false,
                                            create: false,
                                            type: 'hidden',
                                            list: false
                                        },
                                        SizeDescDet: 
                                        {
                                            title: 'Ukuran (CM)',
                                            edit: false,
                                            create: false,
                                            //options: './controller/con_index_jahit.php?action=siz',
                                            inputClass: 'validate[required]'
                                        }, 
                                        JumlahJahitDet: 
                                        {
                                            title: 'Jumlah Jahit(PCS)',
                                            edit: false,
                                            create: false,
                                            inputClass: 'validate[required]'
                                        },
                                        PremiJahitDet: 
                                        {
                                            title: 'Premi Jahit(Rp)',
                                            edit: false,
                                            create: false,
                                            list:premiView,
                                            inputClass: 'validate[required]'
                                        },
                                        LastEditDet: 
                                        {
                                            title: 'Edit Time',
                                            type: 'date',
                                            displayFormat: 'dd-mm-yy',
                                            create: false,
                                            edit: false
                                        },
                                        UkuranDetail:
                                        {
                                            title: '',
                                            width: '1%',
                                            sorting: false,
                                            edit: false,
                                            create: false,
                                            display: function(item)
                                            {
                                                var $imgb = $('<img src="./images/list_metro.png" title="Edit Items Detail" />');
                                                $imgb.click(function () 
                                                {
                                                    $('#itemTableHasilJahit').jtable('openChildTable',
                                                            $imgb.closest('tr'), //Parent row
                                                            {
                                                                title: 'Daftar Detail Ukuran Jahit '+item.record.KaryawanNameDet,
                                                                paging: true, //Enable paging
                                                                sorting: false, //Enable sorting
                                                                columnResizable: false, //Disable column resizing
                                                                columnSelectable: false, //Disable column selecting
                                                                jqueryuiTheme: true,
                                                                pageSize: 10,

                                                                actions: 
                                                                {
                                                                    listAction: './controller/con_hasil_jahit.php?action=viewUkuranDet&HasilJahitDetId='+item.record.HasilJahitDetId,
                                                                    createAction: './controller/con_hasil_jahit.php?action=createUkuranDet',
                                                                    updateAction: './controller/con_hasil_jahit.php?action=updateUkuranDet',
                                                                    deleteAction: './controller/con_hasil_jahit.php?action=deleteUkuranDet&HasilJahitDetId='+item.record.HasilJahitDetId
                                                                },
                                                                fields: 
                                                                {
                                                                    HasilUkuranDetId: 
                                                                    {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    NoUkuranDet:
                                                                    {
                                                                        title: 'No.',
                                                                        create: false,
                                                                        edit: false,
                                                                        width:"5%",
                                                                        sorting: false
                                                                    },
                                                                    HasilJahitDetIdUkuran: 
                                                                    {
                                                                        type: 'hidden',
                                                                        defaultValue: item.record.HasilJahitDetId
                                                                    },
                                                                    DetailUkuranIdDetail: 
                                                                    {
                                                                        title: 'Ukuran',
                                                                        options: './controller/con_hasil_jahit.php?action=sizDet',
                                                                        inputClass: 'validate[required]'
                                                                    }, 
                                                                    DetailUkuranJumlahDetail: 
                                                                    {
                                                                        title: 'Jumlah',
                                                                        inputClass: 'validate[required]'
                                                                    }, 
                                                                    LastEditDet: 
                                                                    {
                                                                        title: 'Edit Time',
                                                                        type: 'date',
                                                                        displayFormat: 'dd-mm-yy',
                                                                        create: false,
                                                                        edit: false
                                                                    }
                                                                },
                                                                //Validate form when it is being submitted
                                                                formSubmitting: function (event, data) 
                                                                {
                                                                    return data.form.validationEngine('validate');
                                                                },
                                                                //Dispose validation logic when form is closed
                                                                formClosed: function (event, data) 
                                                                {
                                                                    data.form.validationEngine('hide');
                                                                    data.form.validationEngine('detach');
                                                                    
                                                                }
//                                                                ,
//                                                                closeRequested: function(event, data)
//                                                                {
//                                                                    //console.log(data);
//                                                                    $imgb.closest('tr').find('.jtable-child-table-container').jtable('reload');
////                                                                      $imga.find('.jtable-child-table-container').jtable('reload');
////                                                                      $('#itemTableHasilJahit').jtable('load', $imgb.closest('tr'));
//                                                                      $('#itemTableHasilJahit').jtable('closeChildTable', $imgb.closest('tr'));
//                                                                      
//                                                                }
                                                            }, 
                                                            function (data) 
                                                            { //opened handler
                                                                data.childTable.jtable('load');
                                                            });
                                                    });

                                                return $imgb;
                                            }
                                        }
                                    },
                                    //Validate form when it is being submitted
                                    formSubmitting: function (event, data) 
                                    {
                                        return data.form.validationEngine('validate');
                                    },
                                    //Dispose validation logic when form is closed
                                    formClosed: function (event, data) 
                                    {
                                        data.form.validationEngine('hide');
                                        data.form.validationEngine('detach');
                                    },
                                    formCreated: function(event, data)
                                    {
                                        $("#cmdSearchKaryawan").button();
                                        $("#cmdSearchKaryawan").click(function()
                                        {
                                            loadTableKaryawan(item);
                                            $("#itemsTableSearchKaryawan").jtable("load");
                                            $( "#itemsTableSearchKaryawan" ).dialog( "open" );
                                        });
                                    }
//                                    ,
//                                    closeRequested: function(event, data)
//                                    {
////                                        console.log(item);
////                                                                    $img.find('.jtable-child-table-container').jtable('reload');
//                                    }
                                }, 
                                function (data) 
                                { //opened handler
                                    data.childTable.jtable('load');
                                });
                        });
                    
                    return $imga;
                }
            }
        },
        //Validate form when it is being submitted
        formSubmitting: function (event, data) 
        {
            return data.form.validationEngine('validate');
        },
        //Dispose validation logic when form is closed
        formClosed: function (event, data) 
        {
            data.form.validationEngine('hide');
            data.form.validationEngine('detach');
        }
    });  
    //$('#itemTableHasilJahit').jtable('load');
	$('#itemTableHasilJahit').jtable('load',{
        datePeriode:$("#txtDatePeriode").val()
    });
    
    $('#itemsTableSearchKaryawan').dialog({autoOpen: false,height: 500,width: 850,modal: true});
    
});

function loadTableKaryawan(item)
{
    $('#itemsTableSearchKaryawan').jtable(
    {
        title: 'Karyawan',
        paging: true, //Enable paging
        sorting: false, //Enable sorting
        columnResizable: false, //Disable column resizing
        columnSelectable: false, //Disable column selecting
        jqueryuiTheme: true,
        pageSize: 10,

        actions: 
        {
            listAction: './controller/con_hasil_jahit.php?action=viewKaryawan&HasilJahitId='+item.record.HasilJahitId+'&HangingId='+item.record.HangingId
        },
        fields: 
        {
            KaryawanId: 
            {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            PinId: 
            {
                title: 'PIN'
            },
            KaryawanName: 
            {
                title: 'Nama Karyawan'
            }
        },
        recordsLoaded: function(event, data) 
        {
//            console.log(data);
            $('.jtable-data-row').click(function() 
            {
                var $row_id = $(this).index();
                $('#Edit-KaryawanIdDet').val(data.records[$row_id].KaryawanId);
                $('#PinIdDet').val(data.records[$row_id].PinId);
                $('#KaryawanNameDet').val(data.records[$row_id].KaryawanName);
                
                $('#itemsTableSearchKaryawan').dialog( "close" );
                $('.jtable-data-row').off("click");
            });
        }
    });
}