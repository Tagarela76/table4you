$(function() {
    $("#sonata_user_profile_form_new_first").keyup(function() {
        checkPasswordFit();
    });
    $("#sonata_user_profile_form_new_second").keyup(function() {
        checkPasswordFit();
    });
});

function checkPasswordFit() {
    var passwordFirst = $("#sonata_user_profile_form_new_first").val();
    var passwordSecond = $("#sonata_user_profile_form_new_second").val();

    if (passwordSecond == passwordFirst) {
        $("#bothPasswordsMatchInProfile").css("display", "block");
    } else {
        $("#bothPasswordsMatchInProfile").css("display", "none");
    }
}