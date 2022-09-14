var fbTemplate = document.getElementById("fb-template");
var form_data = $("#form_json");
const fbRender = $(".fb-render").formRender({
    container: false,
    dataType: "json",
    formData: form_data.val(),
});

function getFormData() {
    let formData = [];
    formData = fbRender.userData.filter(function(currField){
        return currField.type !== "button"
    });

    $("#data_filled").val(JSON.stringify(formData));
    return true;
}

