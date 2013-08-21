$(function() {
    $("#fos_user_registration_form_plainPassword_first").keyup(function() {
        checkPasswordFit();
    });
    $("#fos_user_registration_form_plainPassword_second").keyup(function() {
        checkPasswordFit();
    });
});

function checkPasswordFit() {
    var passwordFirst = $("#fos_user_registration_form_plainPassword_first").val();
    var passwordSecond = $("#fos_user_registration_form_plainPassword_second").val();

    if (passwordSecond == passwordFirst) {
        $("#bothPasswordsMatch").css("display", "block");
    } else {
        $("#bothPasswordsMatch").css("display", "none");
    }
}