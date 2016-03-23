/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */

$(document).ready(function(){
    $('#itemTableIndexJahit').jtable(
    {
        title: 'Master Index Jahit',
        paging: true, //Enable paging
        sorting: false, //Enable sorting
        columnResizable: false, //Disable column resizing
        columnSelectable: false, //Disable column selecting
        jqueryuiTheme: true,
        pageSize: 10,

        actions: 
        {
            listAction: './controller/con_index_jahit.php?action=view',
            createAction: './controller/con_index_jahit.php?action=create',
            updateAction: './controller/con_index_jahit.php?action=update'
            //deleteAction: './controller/con_hanging.php?action=delete'
        },
        fields: 
        {
            IndexId: 
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
            IndexTarget: 
            {
                title: 'Nama Target',
                edit: false,
                inputClass: 'validate[required]'
            },
            JenisId: 
            {
                title: 'Jenis Jahit',
                options: './controller/con_index_jahit.php?action=opt',
                inputClass: 'validate[required]'
            },
            IndexJamKerja: 
            {
                title: 'Jam Kerja',
                options: './controller/con_index_jahit.php?action=jamker',
                inputClass: 'validate[required]'
            },
            IndexIncentif: 
            {
                title: 'Incentif',
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
                    var $img = $('<img src="./images/list_metro.png" title="Edit Items Detail" />');
                    $img.click(function () 
                    {
                        $('#itemTableIndexJahit').jtable('openChildTable',
                                $img.closest('tr'), //Parent row
                                {
                                    title: 'Daftar Range Target '+item.record.IndexTarget,
                                    paging: true, //Enable paging
                                    sorting: false, //Enable sorting
                                    columnResizable: false, //Disable column resizing
                                    columnSelectable: false, //Disable column selecting
                                    jqueryuiTheme: true,
                                    pageSize: 10,

                                    actions: 
                                    {
                                        listAction: './controller/con_index_jahit.php?action=viewDet&IndexIdDet='+item.record.IndexId,
                                        createAction: './controller/con_index_jahit.php?action=createDet',
                                        updateAction: './controller/con_index_jahit.php?action=updateDet',
                                        deleteAction: './controller/con_index_jahit.php?action=deleteDet'
                                    },
                                    fields: 
                                    {
                                        IndexDetId: 
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
                                        IndexIdDet: 
                                        {
                                            type: 'hidden',
                                            defaultValue: item.record.IndexId
                                        },
                                        SizeId: 
                                        {
                                            title: 'Ukuran',
                                            edit: false,
                                            options: './controller/con_index_jahit.php?action=siz',
                                            inputClass: 'validate[required]'
                                        }, 
                                        TargetMin: 
                                        {
                                            title: 'Target Min(PCS)',
                                            inputClass: 'validate[required]'
                                        },
                                        TargetFrom: 
                                        {
                                            title: 'Target(PCS) From',
                                            inputClass: 'validate[required]'
                                        },
                                        TargetUntil: 
                                        {
                                            title: 'Target(PCS) Until',
                                            inputClass: 'validate[required]'
                                        },
                                        TargetMax: 
                                        {
                                            title: 'Target(PCS) Max',
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
                                }, 
                                function (data) 
                                { //opened handler
                                    data.childTable.jtable('load');
                                });
                        });
                    
                    return $img;
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
    
    $('#itemTableIndexJahit').jtable('load');
});