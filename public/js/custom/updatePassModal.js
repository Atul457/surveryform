$(document).ready(function () {
    $("#updatePassBtn").on("click", function () {
        let pass, cpass;
        pass = $("#upPass_password");
        cpass = $("#upPass_cpassword");

        if (updatePassForm.length) {
            $("#updatePassForm").find(".error").remove();

            if (pass.val().length < 6 || pass.val() === "")
                return pass.after(
                    "<div class='error'>password's minimun length is 6.</div>"
                );

            if (cpass.val().length < 6 || pass.val() === "")
                return cpass.after(
                    "<div class='error'>confirm password's minimun length is 6.</div>"
                );

            if (pass.val() !== cpass.val())
                return cpass.after(
                    "<div class='error'>password and confirm password are not matching.</div>"
                );

            data = { password: pass.val() };

            $("#updatePassBtn").html(
                ` <div class="spinner-grow text-white spinner-grow-sm" role="status"> </div>`
            );
            $.ajax({
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: `${window.origin}${$("#is_admin").val() == 1 ? "/admin/updatepass" :"/updatepass"}`,
                data: data,
                success: function (data) {
                    $("#updatePassModal").modal("hide");
                    $("#updatePassBtn").html("Update");
                    pass.val("");
                    cpass.val("");
                },
                error: function (err) {
                    cpass.after(
                        `<div class='error mb-0'>${err.responseText}</div>`
                    );
                    $("#updatePassBtn").html("Update");
                },
            });
        }
    });
});
