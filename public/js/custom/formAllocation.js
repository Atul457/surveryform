var companiesSelectBox = $("#companiesSelectBox"),
    companiesSelectBoxForForms = $("#companiesSelectBoxForForms"),
    productsSelectBox = $("#product_id"),
    usersSelectBox = $("#usersSelectBox"),
    formsOfProd = $("#formsOfProd"),
    formsSelectedArr = [];

if (companiesSelectBox.length)
    companiesSelectBox.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
            dropdownAutoWidth: true,
            width: "100%",
            dropdownParent: $this.parent(),
        });
    });

if (usersSelectBox.length)
    usersSelectBox.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
            dropdownAutoWidth: true,
            width: "100%",
            dropdownParent: $this.parent(),
        });
    });

if (companiesSelectBoxForForms.length)
    companiesSelectBoxForForms.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
            dropdownAutoWidth: true,
            width: "100%",
            dropdownParent: $this.parent(),
        });
    });

if (productsSelectBox.length)
    productsSelectBox.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
            dropdownAutoWidth: true,
            width: "100%",
            dropdownParent: $this.parent(),
        });
    });

function formWrapper(form) {
    if (form.loading) return form.text;
    let comp_id = companiesSelectBoxForForms.val(),
        prod_id = productsSelectBox.val();
    var markup = `<div class='formSelector' id="form-${comp_id}-${prod_id}-${form.id}" onclick="addForm(${comp_id}, ${prod_id}, ${form.id}, '${form.text}')">${form.text}</div>`;
    return $(markup);
}

function getUsersOfComp() {
    let company_selected = companiesSelectBox.val(),
        usersSelectBox = $("#usersSelectBox"),
        usersHtml = "",
        id = null,
        user_name = "";

    $.ajax({
        url: `${window.location.origin}/survey/public/getusersofcomp/${company_selected}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
            if (data.length === 0)
                usersHtml += `<option>No users found</option>`;
            else
                data.forEach((user) => {
                    id = user.user_id;
                    user_name = user.user_name;
                    usersHtml += `<option value="${id}">${user_name}</option>`;
                });
            usersSelectBox.html(usersHtml);
        })
        .fail(function (err) {
            console.log(err);
        });
}

function getFormsOfProd(isInitialLoad = false) {
    let prodSelected = productsSelectBox.val(),
        comp_id = companiesSelectBoxForForms.val(),
        prod_id = productsSelectBox.val(),
        formsPillsHtml = "",
        id = null,
        form_name = "";

    $.ajax({
        url: `${window.location.origin}/getformsofprod/${prodSelected}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
            if (data.length === 0)
                formsPillsHtml += `<span>No forms found</span>`;
            else
                data.forEach((form) => {
                    id = form.form_id;
                    form_name = form.form_name;
                    formsPillsHtml += `<span class="formPills" onclick="addForm(${comp_id}, ${prod_id}, ${id}, '${form_name}')">${form_name}</span>`;
                });
            formsOfProd.html(formsPillsHtml);
        })
        .fail(function (err) {
            console.log(err);
        });
}

function getProductOfComp(isInitialLoad = false) {
    let company_selected = companiesSelectBoxForForms.val(),
        productSelectBox = $("#product_id"),
        selected_prod_hidden = $("#selected_prod_hidden"),
        productsHtml = "",
        id = null,
        prod_name = "";

    $.ajax({
        url: `${window.location.origin}/survey/public/getprodofcomp/${company_selected}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];

            if (data.length === 0)
                productsHtml += `<option>No products found</option>`;
            else
                data.forEach((prod) => {
                    id = prod.id;
                    prod_name = prod.prod_name;
                    productsHtml += `<option value="${id}">${prod_name}</option>`;
                });

            productSelectBox.html(productsHtml);
            if (isInitialLoad && selected_prod_hidden.length) {
                productSelectBox.val(selected_prod_hidden.val());
                productSelectBox.change();
            }

            getFormsOfProd();
        })
        .fail(function (err) {
            console.log(err);
        });
}

getUsersOfComp();
getProductOfComp(true);

$("#formsSelectBox").click(function () {
    console.log($(this));
});

function addForm(comp_id, prod_id, form_id, form_name) {
    let selectedForms = $("#selectedForms"),
        isDuplicateForm = false,
        arrElem = "",
        comp_name = $("#companiesSelectBoxForForms :selected").text().trim(),
        prod_name = $("#product_id :selected").text().trim(),
        html = selectedForms.html();

    $(".selectedFormsCont").find(".error").remove();

    if (selectedForms.find(".itemAdded").length == 0) html = "";
    arrElem = `cid${comp_id}pid${prod_id}fid${form_id}`;
    isDuplicateForm =
        formsSelectedArr.filter(function (formElem) {
            return formElem == arrElem;
        }).length == 0
            ? false
            : true;

    if (!isDuplicateForm) {
        formsSelectedArr = [...formsSelectedArr, arrElem];
        html += `<div class="itemAdded" id="item${arrElem}">
                    <span class="comp_name identifiers">${comp_name}</span>
                    <span class="prod_name identifiers">${prod_name}</span>
                    <span class="form_name identifiers">${form_name}</span>
                    <input 
                        type="hidden"
                        class="form_id_holder"
                        id="formIdHolder${arrElem}"
                        value="${form_id}"
                        name="form_ids[]"/>
            <span class="removeItemAdded" id="cross${arrElem}" onclick="removeAddedForm('${arrElem}')">
                ${feather.icons["x"].toSvg({})}
            <span>
        </div>`;

        selectedForms.html(html);
        return false;
    }

    selectedForms.after(
        "<div class='error'>The form you are trying to add to the list has already been added.</div>"
    );
}

function testIsNumber(value) {
    return !isNaN(value);
}

function allocateForm() {
    let formIdHolders = $(".form_id_holder"),
        selectedForms = $("#selectedForms"),
        surveyAllocationForm = $("#surveyAllocationForm"),
        usersSelectBox = $("#usersSelectBox"),
        areasSelectBox = $("#areasSelectBox");

    $(surveyAllocationForm).find(".error").remove();

    if (!testIsNumber(usersSelectBox.val())) {
        usersSelectBox
            .parent()
            .after("<div class='error'>Please select a user.</div>");
        return false;
    }

    if (!testIsNumber(areasSelectBox.val())) {
        areasSelectBox
            .parent()
            .after("<div class='error'>Please select a area.</div>");
        return false;
    }

    if (formIdHolders.length == 0) {
        selectedForms.after(
            "<div class='error'>At least one form should be selected for form allocation.</div>"
        );
        return false;
    }

    return true;
}

function removeAddedForm(formIdToRemove) {
    let selectedForms = $("#selectedForms");
    $(".selectedFormsCont").find(".error").remove();

    formsSelectedArr = formsSelectedArr.filter(function (formId) {
        return formId != formIdToRemove;
    });

    $(`#item${formIdToRemove}`).remove();
    if (selectedForms.find(".itemAdded").length == 0) {
        html = "No forms selected";
        selectedForms.html(html);
    }
}

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

areasSelectBox.each(function () {
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

function showRelatedAreas(ref) {
    let city_id = ref.value;
    getAreas(city_id);
}

function getAreas(city_id) {
    let areasSelectBox = $("#areasSelectBox"),
        areasHtml = "",
        id = null,
        area_name = "";

    $.ajax({
        url: `${window.location.origin}/getareas/${city_id}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
            if (data.length === 0)
                areasHtml += `<option>No areas found</option>`;
            else
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
