document.addEventListener('DOMContentLoaded', function (e) {
    $('#ams_error_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/errors",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'profile_id', name: 'profile_id' },
            { data: 'name', name: 'name' },
            { data: 'country_code', name: 'country_code' },
            { data: 'type', name: 'type' },
            { data: 'sub_type', name: 'sub_type' },
            { data: 'error_type', name: 'error_type' },
            { data: 'report_date', name: 'report_date' },
            { data: 'sent', name: 'sent' },
            { data: 'captured_at', name: 'captured_at' },
        ]
    });
});
