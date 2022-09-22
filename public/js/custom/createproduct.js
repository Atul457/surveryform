const createForm = () => {
    let prod_name = $("#prod_name");
        let batch_no = $("#batch_no");
        let city_name = $("#city_name");
        let sample_size = $("#sample_size");
        $("#createProdForm").find(".error").remove();
        if (prod_name.val() === "") {
            prod_name.after(
                "<div class='error'>product name is required.</div>"
            );
            return false;
        }
        if (prod_name.val().trim().length < 2) {
            prod_name.after(
                "<div class='error'>product name's mininum length is 2.</div>"
            );
            return false;
        }
        
        if (batch_no.val().trim() === "") {
            batch_no.after(
                "<div class='error'>batch number is required.</div>"
            );
            return false;
        }
        
        if (city_name.val().trim() === "") {
            city_name.after(
                "<div class='error'>city name is required.</div>"
            );
            return false;
        }
        if (city_name.val().trim().length < 2) {
            city_name.after(
                "<div class='error'>city name's mininum length is 2.</div>"
            );
            return false;
        }
        
        if (!parseInt(sample_size.val())) {
            sample_size.after(
                "<div class='error'>please enter a number more than 0.</div>"
            );
            return false;
        }

        return true;
}
