$(document).ready(function () {
    $("#createProdForm").on("submit", function (e) {
        let prod_name = $("#prod_name");
        let batch_no = $("#batch_no");
        let city_name = $("#city_name");
        $("#createProdForm").find(".error").remove();
        if (prod_name.val() === "") {
            e.preventDefault();
            prod_name.after(
                "<div class='error'>product name is required.</div>"
            );
            return false;
        }
        if (prod_name.val().trim().length < 2) {
            e.preventDefault();
            prod_name.after(
                "<div class='error'>product name's mininum length is 2.</div>"
            );
            return false;
        }
        
        if (batch_no.val().trim() === "") {
            e.preventDefault();
            batch_no.after(
                "<div class='error'>batch number is required.</div>"
            );
            return false;
        }
        
        if (city_name.val().trim() === "") {
            e.preventDefault();
            city_name.after(
                "<div class='error'>city name is required.</div>"
            );
            return false;
        }
        if (city_name.val().trim().length < 2) {
            e.preventDefault();
            city_name.after(
                "<div class='error'>city name's mininum length is 2.</div>"
            );
            return false;
        }
        $(this).submit();
    });
});
