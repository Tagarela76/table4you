$(function() {
    // password validation
    $("#fos_user_registration_form_plainPassword_first").keyup(function() {
        checkPasswordFit();
    });
    
    $("#fos_user_registration_form_plainPassword_second").keyup(function() {
        checkPasswordFit();    
    });
    // sync username with email(on change event)
    $("#fos_user_registration_form_email").keyup(function(){
        $("#fos_user_registration_form_username").val($("#fos_user_registration_form_email").val());
    });
});

function checkPasswordFit() {
    var passwordFirst = $("#fos_user_registration_form_plainPassword_first").val();
    var passwordSecond = $("#fos_user_registration_form_plainPassword_second").val();

    if (passwordSecond == passwordFirst && passwordFirst != "") {
        $("#bothPasswordsMatch").css("display", "block");
    } else {
        $("#bothPasswordsMatch").css("display", "none");
    }
}