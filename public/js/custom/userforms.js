// Datatable
// Filter column wise function
function filterColumn(i, val) {
    if (i == 5) {
        var startDate = $(".start_date").val(),
            endDate = $(".end_date").val();
        if (startDate !== "" && endDate !== "") {
            filterByDate(i, startDate, endDate); // We call our filter function
        }

        $(".dt-advanced-search").dataTable().fnDraw();
    } else {
        $(".dt-advanced-search")
            .DataTable()
            .column(i)
            .search(val, false, true)
            .draw();
    }
}

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function (dateString) {
    var date = new Date(dateString);
    var normalized =
        date.getFullYear() +
        "" +
        "/" +
        ("0" + (date.getMonth() + 1)).slice(-2) +
        "" +
        "/" +
        ("0" + date.getDate()).slice(-2);
    return normalized;
};
// Advanced Search Functions Ends

$(function () {
    var dt_adv_filter_table = $(".dt-advanced-search");
    // Advanced Search
    if (dt_adv_filter_table.length) {
        var dt_adv_filter = dt_adv_filter_table.DataTable({
            ajax: `${baseurl}/getuserforms`,
            order: [[5, "desc"]],
            columns: [
                {
                    data: null,
                    render: function () {
                        return "";
                    },
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "form_name" },
                { data: "form_comp_name" },
                { data: "form_prod_name" },
                { data: "city_name" },
                { data: "area_name" },
                { data: "sample_size" },
                { data: "filled_count" },
                { data: "remaining_count" },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (row === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center">
                                    <span onclick="openShareFormModal(${
                                        row.share_id
                                    })" class="cursor-pointer">
                                        ${feather.icons["share-2"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </span>
                                <div>`;
                    },
                },
                {
                    data: "view_report",
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-centerr">
                                    <a href="${
                                        baseurl
                                    }/viewreport/${value}">
                                        ${feather.icons["eye"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </a>
                                <div>`;
                    },
                },
                {
                    data: "created_at",
                    render: function (value) {
                        if (value === null) return "";
                        return normalizeDate(value);
                    },
                },
                {
                    data: "updated_at",
                    render: function (value) {
                        if (value === null) return "";
                        return normalizeDate(value);
                    },
                }
            ],

            columnDefs: [
                {
                    className: "control",
                    orderable: true,
                    targets: 0,
                },
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            orderCellsTop: true,
            lengthMenu: [5, 10, 25, 50, 75, 100],
            responsive: true,
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: "&nbsp;",
                    next: "&nbsp;",
                },
            },
        });
    }

    // on key up from input field
    $("input.dt-input").on("keyup", function () {
        filterColumn($(this).attr("data-column"), $(this).val());
    });

    // Filter form control to default size for all tables
    $(".dataTables_filter .form-control").removeClass("form-control-sm");
    $(".dataTables_length .form-select")
        .removeClass("form-select-sm")
        .removeClass("form-control-sm");
});

function openShareFormModal(shareId) {
    let shareFormRef = $("#shareFormModal");
    let form_link = $("#form_link");
    form_link.val(`${baseurl}/share/${shareId}`);
    shareFormRef.modal("show");
}

function addField() {
    let html = `<div class="phone_num_fields share_fields">
                    <div class="row shareModalinputs">
                        <input
                            type="text"
                            placeholder="Name"
                            autocomplete="off"
                            class="name form-control">
                        <input
                            type="number"
                            placeholder="Consumer phone no"
                            autocomplete="off"
                            onkeyup="validateNumber(this)"
                            class="number form-control">
                        <input
                            type="text"
                            placeholder="Location"
                            autocomplete="off"
                            class="location form-control">
                    </div>
                    <button
                        type="button"
                        class="btn btn-danger ml-1 remove_phone_btn">Remove</button>
                </div>`;
    $("#cunsumer_inputs_cont").append(html);
}

$(document).on("click", ".remove_phone_btn", function (e) {
    $(this).parent().remove();
});


function shareForm() {
    let consumersArr = [],
        i = 0,
        errorCount = 0;
    const sendMessageBtn = $("#sendMessageBtn");
    $("#shareFormModal").find(".error").remove();
    $(".shareModalinputs").each(function () {
        let phone_num = $(this).children(".number"),
            name = $(this).children(".name"),
            location = $(this).children(".location").val().trim(),
            phone_val = $(phone_num).val().trim();
        name_val = $(name).val().trim();

        if (name_val === "") {
            $(name)
                .parent()
                .append("<div class='error px-0'>name is required.</div>");
            errorCount++;
            return false;
        }

        if (name_val.length < 2) {
            $(name)
                .parent()
                .append(
                    "<div class='error px-0'>name's minimum length is 2 characters.</div>"
                );
            errorCount++;
            return false;
        }

        if (phone_val === "") {
            $(name)
                .parent()
                .append("<div class='error px-0'>phone no is required.</div>");
            errorCount++;
            return false;
        }

        if (phone_val.length > 10 || phone_val.length < 10) {
            $(phone_num)
                .parent()
                .append(
                    "<div class='error px-0'>phone number should be of 10 digits.</div>"
                );
            errorCount++;
            return false;
        }

        if (phone_val !== "" && name_val !== "") {
            consumersArr.splice(i, 0, {
                phone: phone_val,
                name: name_val,
                location,
            });
            i++;
        }
    });

    if (i !== 0 && errorCount === 0 && consumersArr.length > 0) {
        sendMessageBtn.html(
            ` <div class="spinner-border spinner-border-sm text-white" role="status"></div>`
        );

        $.ajax({
            url: `${baseurl}/share_form`,
            type: "post",
            data: { consumersArr },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (data) => {
                $("#shareFormModal .modal-header").after(`
                <div class="text-center text-success response fw-bolder mt-1">
                ${data?.message ?? "Messages sent to successfully."}
                </div>
                `);
                sendMessageBtn.html("Send Message");
                removeMessage();
            },
            error: (err) => {
                $("#shareFormModal .modal-header").after(`
                    <div class="text-center text-error response fw-bolder mt-1">
                        ${err?.responseJSON?.error ?? "Something went wrong"}
                    </div>
                `);
                sendMessageBtn.html("Send Message");
                removeMessage();
            },
        });
    }
}

function removeMessage() {
    setTimeout(() => {
        let target = $(".response");
        target.hide("slow", function () {
            target.remove();
        });
    }, 1000);
}

function showAlert() {
    Swal.fire({
        text: "All the numbers should exactly be of 10 digits, and name must be not empty.",
        icon: "error",
        customClass: {
            confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
    });
}

const validateNumber = (ref) => {
    let value = ref.value;
    value.replaceAll(/^\D*$/g, "");
    ref.value = value;
};

const deallocateForm = (share_id) => {
    let input_elem = $("#share_id");
    $("#deallocateFormModal").modal("show");
    if (input_elem.length) {
        input_elem.val(share_id);
    }
};