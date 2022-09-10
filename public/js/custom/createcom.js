$(document).ready(function () {
    $("#createCompForm").on("submit", function (e) {
        let comp_ref = $("#comp_name");
        let comp_addr = $("#comp_addr");
        let comp_care_no = $("#comp_care_no");

        comp_addr.val(comp_addr.val().trim())
        $("#createCompForm").find(".error").remove();
        if (comp_ref.val() === "") {
            e.preventDefault();
            return comp_ref.after(
                "<div class='error'>company name is required.</div>"
            );
        }
        if (comp_ref.val().length < 2) {
            e.preventDefault();
            return comp_ref.after(
                "<div class='error'>company name's mininum length is 2.</div>"
            );
        }

        if (
            comp_care_no.val() !== "" &&
            (comp_care_no.val().length < 10 || comp_care_no.val().length > 10)
        ) {
            e.preventDefault();
            return comp_care_no.after(
                "<div class='error'>customer care no length should be of 10 digits.</div>"
            );
        }

        if (comp_addr.val() === "") {
            e.preventDefault();
            return comp_addr.after(
                "<div class='error'>company address is required.</div>"
            );
        }

        $(this).submit();
    });
});

const validateNumber = (ref) => {
    let comp_care_no = $("#comp_care_no");
    let value = ref.value;
    value.replaceAll(/^\D*$/g, "");
    comp_care_no.val(value);
};
