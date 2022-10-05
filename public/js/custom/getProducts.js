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

    var dt_adv_filter_table = $(".dt-advanced-search")

    if ($("body").attr("data-framework") === "laravel") {
        assetPath = $("body").attr("data-asset-path");
    }
    // Advanced Search
    if (dt_adv_filter_table.length) {
        var dt_adv_filter = dt_adv_filter_table.DataTable({
            ajax: `${baseurl}/getproducts`,
            order: [[5, "desc"]],
            columns: [
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return "";
                    },
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "comp_name" },
                { data: "prod_name" },
                { data: "batch_no" },
                { data: "city" },
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
                    data: "responsive_id",
                    "orderable": false,
                    render: function (value) {
                        if (value === null) return "";
                        if (!showDeleteIcon) return "";
                        return `<div class="d-flex flex-wrap align-items-center">
                                    <span onclick="deleteProduct(${value})" class="cursor-pointer">
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

function deleteProduct(id) {
    let input_elem = $("#del_product_id");
    $("#deleteProductModal").modal("show");
    if (input_elem.length) {
        input_elem.val(id);
    }
}

function confirmDeleteProduct() {
    let deleteProductForm = $("#deleteProductForm");
    if (deleteProductForm.length) {
        deleteProductForm.submit();
    }
}

function getEditIcon(iconType, compId = 0) {
    // 1 => show edit icon
    // 2 => show delete icon
    switch (iconType) {
        case 1:
            return showEditIcon
                ? `<a href="${baseurl}/editcompany/${compId}">
        ${feather.icons["edit"].toSvg({
            class: "me-1",
        })}
    </a>`
                : "";
        case 2:
            return showDeleteIcon
                ? ` <span onclick="deleteCompany(${compId})" class="cursor-pointer">
                ${feather.icons["trash"].toSvg({
                    class: "text-primary",
                })}
            </span>`
                : "";

        default:
            break;
    }
}
