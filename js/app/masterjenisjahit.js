/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */

$(document).ready(function(){
    $('#itemTableJenisJahit').jtable(
    {
        title: 'Master Jenis Jahit',
        paging: true, //Enable paging
        sorting: false, //Enable sorting
        columnResizable: false, //Disable column resizing
        columnSelectable: false, //Disable column selecting
        jqueryuiTheme: true,
        pageSize: 10,

        actions: 
        {
            listAction: './controller/con_jenis_jahit.php?action=view',
            createAction: './controller/con_jenis_jahit.php?action=create',
            updateAction: './controller/con_jenis_jahit.php?action=update'
            //deleteAction: './controller/con_hanging.php?action=delete'
        },
        fields: 
        {
            JenisId: 
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
            JenisDescription: 
            {
                title: 'Jenis',
                edit: false,
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
    
    $('#itemTableJenisJahit').jtable('load');
});