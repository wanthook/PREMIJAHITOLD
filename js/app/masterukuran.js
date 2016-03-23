/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */

$(document).ready(function(){
    
    $('#itemTableUkuran').jtable(
    {
        title: 'Master Ukuran',
        paging: true, //Enable paging
        sorting: false, //Enable sorting
        columnResizable: false, //Disable column resizing
        columnSelectable: false, //Disable column selecting
        jqueryuiTheme: true,
        pageSize: 10,

        actions: 
        {
            listAction: './controller/con_ukuran.php?action=view',
            createAction: './controller/con_ukuran.php?action=create',
            updateAction: './controller/con_ukuran.php?action=update'
            //deleteAction: './controller/con_hanging.php?action=delete'
        },
        fields: 
        {
            No:
            {
                title: 'No.',
                create: false,
                edit: false,
                width:"5%",
                sorting: false
            },
            UkuranId: 
            {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            UkuranDescription: 
            {
                title: 'Ukuran (CM)',
                inputClass: 'validate[required]'
            },
            Flag: 
            {
                title: 'Flag',
                options: {'1':'Aktif','0':'Tidak Aktif'},
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
                        $('#itemTableUkuran').jtable('openChildTable',
                                $img.closest('tr'), //Parent row
                                {
                                    title: 'Detail Ukuran '+item.record.UkuranDescription,
                                    paging: true, //Enable paging
                                    sorting: false, //Enable sorting
                                    columnResizable: false, //Disable column resizing
                                    columnSelectable: false, //Disable column selecting
                                    jqueryuiTheme: true,
                                    pageSize: 10,

                                    actions: 
                                    {
                                        listAction: './controller/con_ukuran.php?action=viewDet&UkuranIdDet='+item.record.UkuranId,
                                        createAction: './controller/con_ukuran.php?action=createDet',
                                        updateAction: './controller/con_ukuran.php?action=updateDet' /*,
                                        deleteAction: './controller/con_ukuran.php?action=deleteDet'*/
                                    },
                                    fields: 
                                    {
                                        ItemDetId: 
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
                                        UkuranIdDet: 
                                        {
                                            type: 'hidden',
                                            defaultValue: item.record.UkuranId
                                        },
                                        UkuranDetailCode: 
                                        {
                                            title: 'Kode',
                                            inputClass: 'validate[required]'
                                        }, 
                                        UkuranDetailDescription: 
                                        {
                                            title: 'Ukuran',
                                            edit: false,
                                            inputClass: 'validate[required]'
                                        }, 
                                        FlagDet: 
                                        {
                                            title: 'Flag',
                                            options: {'1':'Aktif','0':'Tidak Aktif'},
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
    
    $('#itemTableUkuran').jtable('load');
});