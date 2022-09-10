var fbTemplate = document.getElementById("fb-template");
var form_data = $("#form_json");
const fbRender = $(".fb-render").formRender({
    container: false,
    dataType: "json",
    formData: form_data.val(),
});

function getData() {
    // console.log(fbRender.userData);
}
