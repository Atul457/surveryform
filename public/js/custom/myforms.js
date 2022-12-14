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

var dt_adv_filter_table, dt_adv_filter;

$(function () {
    dt_adv_filter_table = $(".dt-advanced-search");
    // Advanced Search
    if (dt_adv_filter_table.length) {
        dt_adv_filter = dt_adv_filter_table.DataTable({
            ajax: `${baseurl}/getMyforms`,
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
                {
                    data: "form_name",
                    render: function (data, type, row) {
                        const is_copied = row.is_copied == "1";
                        const copiedFormHtml = is_copied
                            ? ` <span class="badge rounded-pill badge-light-success">Copied</span>`
                            : "";
                        return `<span>${row?.form_name}</span> ${copiedFormHtml}`;
                    },
                },
                { data: "comp_name" },
                { data: "start_date" },
                { data: "end_date" },
                {
                    data: "status",
                    render: function (value) {
                        if (value === null) return "";
                        return `<span class="badge m-1 rounded-pill badge-light-${
                            value === 0 ? "danger" : "success"
                        }"}>${value === 0 ? "Inactive" : "Active"}</span>`;
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
                },
                {
                    data: "forms_allocated",
                    render: function (value) {
                        if (value === null) return "";
                        if (!showAllocatedtoIcon) return "";
                        return `<div class="d-flex flex-wrap align-items-centerr">
                                    <a href="${baseurl}/formsallocatedview/${value}">
                                        ${feather.icons["eye"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </a>
                                <div>`;
                    },
                },
                {
                    data: "share_id",
                    render: function (value) {
                        if (value === null) return "";
                        if (!showAllocatedtoIcon) return "";
                        return `<div class="d-flex flex-wrap align-items-centerr">
                                    <a href="${baseurl}/formsallocatedview/${value}">
                                        ${feather.icons["share-2"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </a>
                                <div>`;
                    },
                },
                {
                    data: "view_report",
                    render: function (value) {
                        if (value === null) return "";
                        if (!showViewReportIcon) return "";
                        return `<div class="d-flex flex-wrap align-items-centerr">
                                    <a href="${baseurl}/view_report_admin/${
                            value ?? 0
                        }">
                                        ${feather.icons["eye"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </a>
                                <div>`;
                    },
                },
                {
                    data: "copy_form",
                    render: function (value) {
                        if (value === null) return "";
                        if (!showDuplicateFormIcon) return "";
                        return `<div class="d-flex flex-wrap align-items-center">
                                    <span class="cursor-pointer" onclick="copyForm(${value})">
                                        ${feather.icons["copy"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </span>
                                <div>`;
                    },
                },
                {
                    data: "action",
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center">
                        ${getEditIcon(1, value)}
                        ${getEditIcon(2, value)}
                     <div>`;
                    },
                },
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

function getEditIcon(iconType, formId = 0) {
    // 1 => show edit icon
    // 2 => show delete icon
    switch (iconType) {
        case 1:
            return showEditIcon
                ? `<a href="${baseurl}/editform/${formId}">
        ${feather.icons["edit"].toSvg({
            class: "me-1",
        })}
    </a>`
                : "";
        case 2:
            return showDeleteIcon
                ? ` <span onclick="deleteSurveyForm(${formId})" class="cursor-pointer">
                ${feather.icons["trash"].toSvg({
                    class: "text-primary",
                })}
            </span>`
                : "";

        default:
            break;
    }
}

function deleteSurveyForm(id) {
    let input_elem = $("#del_survey_id");
    $("#deleteSurveyFormModal").modal("show");
    if (input_elem.length) {
        input_elem.val(id);
    }
}

function confirmDeleteSuveyForm() {
    let deleteSurveyForm = $("#deleteSuveyForm");
    if (deleteSurveyForm.length) {
        deleteSurveyForm.submit();
    }
}

function copyForm(formId) {
    $.ajax({
        url: `${baseurl}/duplicateform/${formId}`,
        method: "get",
        success: (data) => {
            console.log(data);
            dt_adv_filter.ajax.reload();
            $("#advanced-search-datatable").before(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    Form duplicated successfully.
                </div>
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            removeAlerts();
        },
        error: (err) => {
            $("#advanced-search-datatable").before(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    ${err?.responseJSON?.error ?? "Something went wrong"}
                </div>
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        },
    });
}

function removeAlerts() {
    setTimeout(() => {
        let target = $(".alert");
        target.hide("slow", function () {
            target.remove();
        });
    }, 1000);
}
