document.addEventListener('DOMContentLoaded', function(e) {

    //View all admin in datatabale
    $('#notification_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"/notification",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'alert_name',name: 'alert_name' },
            { data: 'vendor',name: 'vendor' },
            { data: 'report_range',name: 'report_range' },
            { data: 'trigger_date',name: 'trigger_date' },
            { data: 'action',name: 'action', orderable: false },
        ],
        order: [ [3, 'desc'] ],
    }).columns.adjust().draw();

    $('#mark_all_as_read').click(function(){
        //set CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : base_url+"/notification/mark/read/all",
            type: "GET",
            dataType:"json",
            success: function (data) {
                //Reload datatable
                if(data.success){
                    $('#notification_table').DataTable().ajax.reload(null,false);
                }
            }
        });
    });

    $(document).on('click', '.disable', function () {
        //get alert ID
        var id = $(this).attr('id');
        //set CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : base_url+"/notification/mark/"+id+"/disable",
            type: "POST",
            dataType:"json",
            success: function (data) {
                //Reload datatable
                if(data.success){
                    toastr.info( data.success, "Removed!", {
                        "closeButton": true,
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 1000
                    });
                    $('#notification_table').DataTable().ajax.reload(null,false);
                }
            }
        });
    });
});
