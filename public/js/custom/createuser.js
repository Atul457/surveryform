$(document).ready(function () {
    $("#createUserForm").on("submit", function (e) {
        let user_name = $("#user_name");
        let user_email = $("#user_email");
        let user_password = $("#user_password");
        let user_update_password = $("#user_update_password");
        let employee_code = $("#employee_code");
        let phone_no = $("#phone_no");
        $("#createUserForm").find(".error").remove();
        let count = 0;

        if (user_name.val().trim() === "") {
            e.preventDefault();
            user_name.after(
                "<div class='error'>employee name is required.</div>"
            );
            count++;
            return false;
        }
        if (user_name.val().length < 2) {
            e.preventDefault();
            user_name.after(
                "<div class='error'>employee name's mininum length is 2.</div>"
            );
            count++;
            return false;
        }

        if (user_email.val() === "") {
            e.preventDefault();
            user_email.after(
                "<div class='error'>employee email is required.</div>"
            );
            count++;
            return false;
        }

        if (!validateEmail(user_email.val())) {
            e.preventDefault();
            user_email.after(
                "<div class='error'>employee email is not valid.</div>"
            );
            count++;
            return false;
        }

        if (user_password.length && user_password.val() === "") {
            e.preventDefault();
            user_password.after(
                "<div class='error'>employee password is required.</div>"
            );
            count++;
            return false;
        }
        if (user_password.length && user_password.val().length < 6) {
            e.preventDefault();
            user_password.after(
                "<div class='error'>employee password's mininum length is 6.</div>"
            );
            count++;
            return false;
        }

        if (
            user_update_password.length &&
            user_update_password.val().length > 0 &&
            user_update_password.val().length > 6
        ) {
            e.preventDefault();
            user_update_password.after(
                "<div class='error'>employee password's mininum length is 6.</div>"
            );
            count++;
            return false;
        }

        if (employee_code.val().trim() === "") {
            e.preventDefault();
            employee_code.after(
                "<div class='error'>employee code is required.</div> "
            );
            count++;
            return false;
        }

        if ((phone_no.val().trim().length < 10 || phone_no.val().trim().length > 10)) {
            e.preventDefault();
            phone_no.after(
                "<div class='error'>phone no length should be of 10 digits.</div>"
            );
            count++;
            return false;
        }

        if (count === 0) $(this).submit();
    });
});

function validateEmail(email) {
    var re =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

const validateNumber = (ref) => {
    let phone_no = $("#phone_no");
    let value = ref.value;
    value.replaceAll(/^\D*$/g, "");
    phone_no.val(value);
};
