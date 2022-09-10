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
    var isRtl = $("html").attr("data-textdirection") === "rtl";

    var dt_adv_filter_table = $(".dt-advanced-search");
    // assetPath = "../../../app-assets/";

    if ($("body").attr("data-framework") === "laravel") {
        assetPath = $("body").attr("data-asset-path");
    }
    // Advanced Search
    if (dt_adv_filter_table.length) {
        var dt_adv_filter = dt_adv_filter_table.DataTable({
            ajax: `${window.location.origin}/getMyforms`,
            order: [[5, "desc"]],
            columns: [
                { data: "responsive_id" },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "form_name" },
                { data: "comp_name" },
                {
                    data: "status",
                    render: function (value) {
                        if (value === null) return "";
                        return `<span class="badge rounded-pill badge-light-${
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
                    data: "action",
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center">
                                    <span onclick="openShareFormModal(${value})" class="cursor-pointer">
                                        ${feather.icons["share-2"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </span>
                                <div>`;
                    },
                },
                {
                    data: "share",
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center">
                                    <a href="${
                                        window.location.origin
                                    }/survey/public/editform/${value}">
                                        ${feather.icons["edit"].toSvg({
                                            class: "me-1",
                                        })}
                                    </a>
                                    <span onclick="deleteSurveyForm(${value})" class="cursor-pointer">
                                        ${feather.icons["trash"].toSvg({
                                            class: "text-primary",
                                        })}
                                    </span>
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
            responsive: {
                details: {
                    type: "column",
                    renderer: function (columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== ""
                                ? '<tr data-dt-row="' +
                                      col.rowIndex +
                                      '" data-dt-column="' +
                                      col.columnIndex +
                                      '">' +
                                      "<td>" +
                                      col.title +
                                      ":" +
                                      "</td> " +
                                      "<td>" +
                                      col.data +
                                      "</td>" +
                                      "</tr>"
                                : "";
                        }).join("");

                        return data
                            ? $('<table class="table"/><tbody />').append(data)
                            : false;
                    },
                },
            },
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

function openShareFormModal(id){
    let shareFormRef = $("#shareFormModal");
    let form_link = $("#form_link");
    form_link.val(`${window.location.origin}/fillupform/${id}`)
    shareFormRef.modal("show");
}