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

    var dt_adv_filter_table = $(".dt-advanced-search"),
        assetPath = "../../../app-assets/";

    if ($("body").attr("data-framework") === "laravel") {
        assetPath = $("body").attr("data-asset-path");
    }
    // Advanced Search
    if (dt_adv_filter_table.length) {
        var dt_adv_filter = dt_adv_filter_table.DataTable({
            ajax: `${baseurl}/getUsers`,
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
                { data: "name" },
                { data: "email" },
                { data: "comp_name" },
                { data: "emp_code" },
                { data: "phone_no" },
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
                    data: "viewpermissions",
                    orderable: false,
                    render: function (value) {
                        if (value === null) return "";
                        if (!showPermissionIcon) return "";
                        return `<div class="d-flex flex-wrap justify-content-center">
                                        ${feather.icons["eye"].toSvg({
                                            class: "me-1 text-primary cursor-pointer",
                                            onclick: `viewUserPermissions(${value})"`,
                                        })}
                                <div>`;
                    },
                },
                {
                    data: "action",
                    orderable: false,
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

function getPermissions(userId) {
    const permissionModules = $("#permissionModules");
    let permHtml = "";
    permissionModules.html("");
    $("#userId").val(userId);

    $.ajax({
        url: `${baseurl}/getpermissions/${userId}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
            const { all_permissions_modules, permissions_assigned } = data;
            // console.table(all_permissions_modules);
            // console.log({ permissions_assigned });

            all_permissions_modules.forEach(({ module_name }) => {
                permHtml += `<div class="permission_module_cont" id="moduleCont${module_name}" name="${module_name}">
                <h5 class="fw-bolder text-capitalize">${module_name}</h5>
                <div class="permission_checkBoxes">
                    ${getPermissionHtml(module_name, permissions_assigned)}
                </div>
              </div>`;
            });
            permissionModules.html(permHtml);
        })
        .fail(function (err) {
            console.log(err);
        });
}

function getPermissionHtml(module_name, permissions_assigned) {
    const permissions = permissions_assigned.filter((curr) => {
        return curr.module_name == module_name;
    });
    let havePermission = permissions[0]?.permissions
        ? JSON.parse(permissions[0]?.permissions)
        : [];
    let permissionHtml = "";
    const pemissionsArr = ["create", "view", "edit", "delete", "update"];
    pemissionsArr.forEach((permission) => {
        permissionHtml += `<div class="checkbox_group">
            <label for="${module_name}_${permission}">${permission}</label>
            <input type="checkbox" name="${permission}" ${
            havePermission.includes(permission) ? "checked" : ""
        } id="${module_name}_${permission}"/>
        </div>`;
    });
    return permissionHtml;
}

function updatePermissions() {
    const moduleNodes = document.querySelectorAll(".permission_module_cont"),
        userId = $("#userId").val(),
        updatePermissionsBtn = $("#updatePermissionsBtn");

    let moduleName,
        updatedPermissionsArr = [];
    (permissionGrantedArr = []), (currModulePermNodes = []);

    moduleNodes.forEach((currNode) => {
        moduleName = currNode.getAttribute("name");
        if (moduleName) {
            currModulePermNodes = currNode.querySelectorAll(`.checkbox_group`);
            var permissionGrantedArrElem = [];
            currModulePermNodes.forEach((currPermNode) => {
                let node = currPermNode.querySelector("[name]");
                let { checked, name } = node;
                if (checked) {
                    permissionGrantedArrElem = [
                        ...permissionGrantedArrElem,
                        name,
                    ];
                }
            });
            updatedPermissionsArr = [
                ...updatedPermissionsArr,
                {
                    moduleName,
                    permissionGrantedArrElem,
                },
            ];
        }
    });

    updatePermissionsBtn.html(
        ` <div class="spinner-border spinner-border-sm text-white" role="status"></div>`
    );

    $.ajax({
        url: `${baseurl}/updatepermissions/${userId}`,
        type: "post",
        data: { updatedPermissionsArr },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: (data) => {
            $("#permissionModules").after(`
                <div class="text-center text-success response fw-bolder mt-1">
                    ${data?.message ?? "Permissions updated successfullly."}
                </div>
            `);
            updatePermissionsBtn.html("Update permissions");
            removeMessage();
        },
        error: (err) => {
            $("#permissionModules").after(`
                <div class="text-center text-error response fw-bolder mt-1">
                    ${err?.responseJSON?.error ?? "Something went wrong"}
                </div>
            `);
            updatePermissionsBtn.html("Update permissions");
            removeMessage();
        },
    });
}

function removeMessage() {
    setTimeout(() => {
        let target = $(".response");
        target.hide("slow", function () {
            target.remove();
        });
    }, 1000);
}

function removeAlerts() {
    setTimeout(() => {
        let target = $(".alert");
        target.hide("slow", function () {
            target.remove();
        });
    }, 1000);
}

function viewUserPermissions(userId) {
    let viewPermissionsModal = $("#viewPermissionsModal");
    viewPermissionsModal.modal("show");
    getPermissions(userId);
}

function getEditIcon(iconType, userId = 0) {
    // 1 => show edit icon
    // 2 => show delete icon
    // 3 => show permission icon
    switch (iconType) {
        case 1:
            return showEditIcon
                ? `<a href="${baseurl}/edituser/${userId}">
        ${feather.icons["edit"].toSvg({
            class: "me-1",
        })}
    </a>`
                : "";
        case 2:
            return showDeleteIcon
                ? ` <span onclick="deleteUser(${userId})" class="cursor-pointer">
                ${feather.icons["trash"].toSvg({
                    class: "text-primary",
                })}
            </span>`
                : "";

        default:
            break;
    }
}

function deleteUser(id) {
    let input_elem = $("#del_user_id");
    $("#deleteUserModal").modal("show");
    if (input_elem.length) {
        input_elem.val(id);
    }
}

function confirmDeleteUser() {
    let deleteUserForm = $("#deleteUserForm");
    if (deleteUserForm.length) {
        deleteUserForm.submit();
    }
}
