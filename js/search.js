$(document).ready(function () {
    $("#player_name").autocomplete({
        serviceUrl: "src/ajaxrequest/get_name.php",
        type: "POST"
    });
});
