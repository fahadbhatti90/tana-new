//get notification from database
updateNotification();

var pusher = new Pusher(PUSHER_APP_KEY, {
    cluster: PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true
});

// Subscribe to the channel we specified in our Laravel Event
var channel = pusher.subscribe('notification');

// Bind a function to a Event (the full Laravel class)
channel.bind('load-report', function(data) {
    toastr.info( data.message, data.title, {
        "closeButton": true,
        "showMethod": "slideDown",
        "hideMethod": "slideUp",
        timeOut: 2000
    });
    //get notification from database
    updateNotification();
});

function updateNotification() {
    let count = 0;
    let notificationContent = "";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/notification/new",
        type: "GET",
        dataType: "json",
        success: function (data) {
            //Reload datatable
            for(var i = 0; i < data.length; i++){
                count++;
                notificationContent += "<a class='d-flex justify-content-between' href='"+base_url+"/notification/show/"+data[i].alert_id+"'>\n" +
                    "                       <div class='media d-flex align-items-start'>\n" +
                    "                              <div class='media-left'><i class='feather icon-file font-medium-5 warning'></i></div>\n" +
                    "                              <div class='media-body'>\n" +
                    "                                     <h6 class='warning media-heading'>"+data[i].alert_name+"</h6>" +
                    "                                     <small class='notification-text text-capitalize'>"+data[i].report_range+" report</small>\n" +
                    "                              </div>" +
                    "                              <small>\n" +
                    "                                      <time class='media-meta'>"+data[i].trigger_date+"</time>" +
                    "                              </small>\n" +
                    "                        </div>\n" +
                    "                   </a>"
            }
            $("#notificationsCount").html(count);
            $("#notificationsHeaderCount").html(count);
            $("#notificationContent").html(notificationContent)
        }
    });
}
