document.addEventListener('DOMContentLoaded', function (e) {
    $('#process_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/process",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Id', name: 'Id' },
            { data: 'User', name: 'User' },
            { data: 'Host', name: 'Host' },
            { data: 'db', name: 'db' },
            { data: 'Command', name: 'Command' },
            { data: 'Time', name: 'Time' },
            { data: 'State', name: 'State' },
            { data: 'Info', name: 'Info' },
        ]
    });
});
