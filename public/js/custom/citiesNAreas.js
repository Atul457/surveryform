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

$(function () {
    var citiesDatatable = $("#citiesDatatable"),
        areasDatatable = $("#areasOfACity");

    // Cities table
    if (citiesDatatable.length) {
        citiesDatatable.DataTable({
            ajax: `${window.location.origin}/survey/public/getcities`,
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
                { data: "city_name" },
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
                    data: "view_areas",
                    orderable: false,
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center justify-content-center">
                                    <a href="${
                                        window.location.origin
                                    }/survey/public/areas/${value}">
                                        ${feather.icons["eye"].toSvg({
                                            class: "me-1",
                                        })}
                                    </a>
                                <div>`;
                    },
                },
                {
                    data: "action",
                    orderable: false,
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center justify-content-center">
                                    <a href="${
                                        window.location.origin
                                    }/survey/public/editcity/${value}">
                                        ${feather.icons["edit"].toSvg({
                                            class: "me-1",
                                        })}
                                    </a>
                                    <span onclick="deleteCity(${value})" class="cursor-pointer">
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

    // Areas datatable
    // Cities table
    if (areasDatatable.length) {
        areasDatatable.DataTable({
            ajax: `${window.location.origin}/survey/public/getareas/${cityId}`,
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
                { data: "area_name" },
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
                    orderable: false,
                    render: function (value) {
                        if (value === null) return "";
                        return `<div class="d-flex flex-wrap align-items-center justify-content-center">
                                     <a href="${
                                         window.location.origin
                                     }/survey/public/editarea/${value}">
                                         ${feather.icons["edit"].toSvg({
                                             class: "me-1",
                                         })}
                                     </a>
                                     <span onclick="deleteArea(${value})" class="cursor-pointer">
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
});

var citiesSelectBox = $("#citiesSelectBox");
var areasSelectBox = $("#areasSelectBox");

citiesSelectBox.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
        dropdownAutoWidth: true,
        width: "100%",
        dropdownParent: $this.parent(),
        templateResult: format,
    });
});

function format(city) {
    if (city.loading) return city.text;

    var markup =
      "<div class='select2-result-repository clearfix'>" +
      city.text +
        "</div>";

    return $(markup);
}

// Loading remote data
areasSelectBox.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
        dropdownAutoWidth: true,
        width: "100%",
        dropdownParent: $this.parent(),
    });
});

function showRelatedAreas(ref) {
    let city_id = ref.value;
    getAreas(city_id);
}

function getAreas(city_id) {
    (areasSelectBox = $("#areasSelectBox")),
        (areasHtml = ""),
        (id = null),
        (area_name = "");

    $.ajax({
        url: `${window.location.origin}/survey/public/getareas/${city_id}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
            data.forEach((area) => {
                id = area.id;
                area_name = area.area_name;
                areasHtml += `<option value="${id}">${area_name}</option>`;
            });
            areasSelectBox.html(areasHtml);
        })
        .fail(function (err) {
            console.log(err);
        });
}

$(document).ready(function () {
    let citiesSelectBox = document.querySelector("#citiesSelectBox"),
    city_id;
    if (citiesSelectBox && citiesSelectBox.length) {
        city_id = citiesSelectBox.value;
        getAreas(city_id);
    }
});

function createCity() {
    if (!validateForm()) return false;
    return true;
}

function validateForm() {
    let city_name = $("#city_name");
    $("#add_city_form").find(".error").remove();
    if (city_name.val() === "") {
        city_name.after("<div class='error'>city name is required.</div>");
        city_name.focus();
        return false;
    }
    if (city_name.val().length < 2) {
        city_name.after(
            "<div class='error'>city's name's mininum length is 2.</div>"
        );
        city_name.focus();
        return false;
    }

    return true;
}

function createArea(params) {
    if (!validateAreaForm()) return false;
    return true;
}

function validateAreaForm() {
    let area_name = $("#area_name");
    $("#add_area_form").find(".error").remove();
    if (area_name.val() === "") {
        area_name.after("<div class='error'>area name is required.</div>");
        area_name.focus();
        return false;
    }
    if (area_name.val().length < 2) {
        area_name.after(
            "<div class='error'>area's name's mininum length is 2.</div>"
        );
        area_name.focus();
        return false;
    }

    return true;
}

function deleteCity(id) {
    let input_elem = $("#del_city_id");
    $("#deleteCityModal").modal("show");
    if (input_elem.length) {
        input_elem.val(id);
    }
}

function confirmDeleteCity() {
    let deleteCityForm = $("#deleteCityForm");
    if (deleteCityForm.length) {
        deleteCityForm.submit();
    }
}

function deleteArea(id) {
    let input_elem = $("#del_area_id");
    $("#deleteAreaModal").modal("show");
    if (input_elem.length) {
        input_elem.val(id);
    }
}

function confirmDeleteArea() {
    let deleteAreaForm = $("#deleteAreaForm");
    if (deleteAreaForm.length) {
        deleteAreaForm.submit();
    }
}
