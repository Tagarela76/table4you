$(function() {
    $("#sonata_user_profile_form_newPassword_first").keyup(function() {
        checkPasswordFit();
    });
    $("#sonata_user_profile_form_newPassword_second").keyup(function() {
        checkPasswordFit();
    });
});

function checkPasswordFit() {
    var passwordFirst = $("#sonata_user_profile_form_newPassword_first").val();
    var passwordSecond = $("#sonata_user_profile_form_newPassword_second").val();

    if (passwordSecond == passwordFirst && passwordFirst != "") {
        $("#bothPasswordsMatch").css("display", "block");
    } else {
        $("#bothPasswordsMatch").css("display", "none");
    }
}