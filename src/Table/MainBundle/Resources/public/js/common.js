function Helper() {

    this.openRegisterWindow = function() {
        
        var registerTitle = $("#registerTitle").val();
        $.ajax({
            url: Routing.generate('fos_user_registration_register_custom'),
            type: "POST",
            dataType: "html",
            data: {justView: 1},
            success: function(responce) {
                $('.modal-header h3').html(registerTitle);
                $('#login .modal-body').html(responce);
            }
        });
    }
}

function Page() {
    this.helper = new Helper();
}

//	global page object
var page;

$(function() {
    //	ini global object
    page = new Page();
});
