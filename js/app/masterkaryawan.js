/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */

$(document).ready(function(){
    
    $('#itemTableKaryawan').jtable(
    {
        title: 'Master Karyawan',
        paging: true, //Enable paging
        sorting: false, //Enable sorting
        columnResizable: false, //Disable column resizing
        columnSelectable: false, //Disable column selecting
        jqueryuiTheme: true,
        pageSize: 10,

        actions: 
        {
            listAction: './controller/con_karyawan.php?action=view',
            createAction: './controller/con_karyawan.php?action=create',
            updateAction: './controller/con_karyawan.php?action=update'
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
            KaryawanId: 
            {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            PinId: 
            {
                title: 'PIN',
                // edit: false,
                inputClass: 'validate[required]'
            },
            KaryawanName: 
            {
                title: 'Nama Karyawan',
                // edit: false,
                inputClass: 'validate[required]'
            },
            HangingId: 
            {
                title: 'Hanging',
                options: './controller/con_karyawan.php?action=opt',
                inputClass: 'validate[required]'
            },
            Flag: 
            {
                title: 'Flag',
                options: {'1':'Aktif','0':'Resign'},
                inputClass: 'validate[required]'
            },
            LastEdit: 
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
    });
    
    $('#itemTableKaryawan').jtable('load');
});