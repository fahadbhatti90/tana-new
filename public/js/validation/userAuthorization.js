$(document).ready(function(){

    $(document).on('click', '#auth_action_button', function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, save it!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                var result = submitData();

                Swal.fire({
                    type: "success",
                    title: 'Saved!',
                    allowOutsideClick: false,
                    text: "Your changes are saved",
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result) {
                    window.location = redirect_url;
                });
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    allowOutsideClick: false,
                    text: 'Your Imaginary data is safe!',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        });
    });

    $(document).on('click', '#back', function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do You want to save changes",
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, save it!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonText: 'No, discard it!',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {

                var result = submitData();

                Swal.fire({
                    type: "success",
                    title: 'Saved!',
                    allowOutsideClick: false,
                    text: "Your changes are saved",
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result) {
                    window.location = redirect_url;
                });
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location = redirect_url;
            }
        });
    });


    function submitData(){

        var action_url = base_url+"/user/authorization/update/"+$('#hidden_id').val();
        var action_method = "POST";

        //ajax call
        $.ajax({
            url:action_url,
            type:action_method,
            data: $('#addUserAuth_form').serialize(),
            dataType:"json",
            cache: false,
            success:function(data){
                if(data.success == 'User authorizations are refreshed'){
                    return data;
                }
            },
        });
    }

});
