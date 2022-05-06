document.addEventListener('DOMContentLoaded', function (e) {
    //View all admin in datatable
    $('#bid_rule_history_table').DataTable({
        processing: true,
        serverSide: true,
        ordering : true,
        ajax: {
            url: base_url+"/biddingRule",
        }, // end ajax
        language: {
            "emptyTable": "No data found"
        }, // end language
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false},
            { data: 'rule_name',name: 'rule_name' },
            { data: 'rule_select_type',name: 'rule_select_type' },
            { data: 'included',name: 'included', orderable: false, searchable: false},
            { data: 'pre_set_rule_name',name: 'pre_set_rule_name' },
            { data: 'look_back',name: 'look_back' },
            { data: 'frequency',name: 'frequency' },
            { data: 'statement',name: 'statement' },
            { data: 'executed_at',name: 'executed_at' , orderable: false, searchable: false  },
            { data: 'status',name: 'status'},
            { data: 'action',name: 'action', orderable: false, searchable: false },
        ], // end columns
        "lengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        order: [ [1, 'asc'] ],
        initComplete: function( settings, json ) {
            $('.dataList').popover();
        }, // end initComplete
        fnInitComplete: function() {
            $('.dataList').popover();
        }, // end fnInitComplete
        fnDrawCallback: function() {
            $('.dataList').popover();
        }, // end fnDrawCallback
    }).columns.adjust().draw(); // end DataTable
}); // end addEventListener



