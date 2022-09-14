var citiesSelectBox = $("#citiesSelectBox");
var areasSelectBox = $("#areasSelectBox");

citiesSelectBox.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
        dropdownAutoWidth: true,
        width: "100%",
        dropdownParent: $this.parent(),
        templateResult: format
    });
});

function format(city) {
    if (city.loading) return city.text;

    var markup =
      "<div class='select2-result-repository clearfix'>" +
      city.text +
      '</div>';

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
        city_id = citiesSelectBox.value;
    if (citiesSelectBox.length) getAreas(city_id);
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
