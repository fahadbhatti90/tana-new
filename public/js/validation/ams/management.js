document.addEventListener('DOMContentLoaded', function(e) {
    //View all manager in datatabale
    $('#cron_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"/ams/dashboard",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false},
            { data: 'client_id',name: 'client_id' },
            { data: 'client_secret',name: 'client_secret' },
            { data: 'number_of_profiles',name: 'number_of_profiles' },
            { data: 'creation_date',name: 'creation_date' },
            { data: 'status',name: 'status', orderable: false }
        ],
        "lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "All"]],
        order: [ [2, 'desc'] ],
    }).columns.adjust().draw();
});
