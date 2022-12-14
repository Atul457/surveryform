const elemRef = $("#formBuilder");
const updatElemRef = $("#formUpdater");
let formUpdaterRef;
let formBuilderRef;
if (elemRef.length) {
    formBuilderRef = elemRef.formBuilder({
        // additional form action buttons- save, data, clear
        actionButtons: [],

        // enables/disables stage sorting
        allowStageSort: true,

        // append/prepend non-editable content to the form.
        append: false,
        prepend: false,

        // control order
        controlOrder: [
            "header",
            "text",
            "file",
            "select",
            "checkbox",
            "checkbox-group",
            "radio-group",
            "autocomplete",
            "paragraph",
            "number",
            "textarea",
            // '<a href="https://www.jqueryscript.net/time-clock/">date</a>',
            "button",
            "hidden",
        ],

        disableFields: [
            "autocomplete",
            "header",
            "file",
            "paragraph",
            "textarea",
            "date",
            "hidden",
            "number",
        ],

        container: true,

        // or left
        controlPosition: "right",

        // or 'xml'
        dataType: "json",

        // default fields
        defaultFields: [],

        // save, data, clear
        disabledActionButtons: ["data", "save", "clear"],

        // disabled attributes
        disabledAttrs: [],

        // disabled buttons
        disabledFieldButtons: {},

        // disabled subtypes
        disabledSubtypes: {},

        // disables html in field labels
        disableHTMLLabels: false,

        // removes the injected style
        disableInjectedStyle: false,

        // opens the edit panel on added field
        editOnAdd: false,

        // adds custom control configs
        fields: [],

        // warns user if before the remove a field from the stage
        fieldRemoveWarn: false,

        // DOM node or selector
        fieldEditContainer: null,

        // add groups of fields at a time
        inputSets: [],

        // custom notifications
        notify: {
            error: console.error,
            success: console.log,
            warning: console.warn,
        },

        // prevent clearAll from remove default fields
        persistDefaultFields: false,

        // callbakcs
        onAddField: (fieldData, fieldId) => {},
        onAddOption: () => null,
        onClearAll: () => null,
        onCloseFieldEdit: () => null,
        onOpenFieldEdit: () => null,
        onSave: (evt, formData) => {},

        // replaces an existing field by id.
        replaceFields: [],

        // user roles
        roles: {
            1: "Administrator",
        },

        // smoothly scrolls to a field when its added to the stage
        scrollToFieldOnAdd: false,

        // shows action buttons
        showActionButtons: true,

        // sortable controls
        sortableControls: false,

        // sticky controls
        stickyControls: {
            enable: true,
            offset: {
                top: 5,
                bottom: "auto",
                right: "auto",
            },
        },

        // defines new types to be used with field base types such as button and input
        subtypes: {},

        // defines a custom output for new or existing fields.
        templates: {},

        // defines custom attributes for field types
        typeUserAttrs: {},

        // disabled attributes for specific field types
        typeUserDisabledAttrs: {},

        // adds functionality to existing and custom attributes using onclone and onadd events. Events return JavaScript DOM elements.
        typeUserEvents: {},
    });
}

if (updatElemRef.length) {
    let formJsonHolder = $("#form_json");
    
    formUpdaterRef = updatElemRef.formBuilder({
        // additional form action buttons- save, data, clear
        actionButtons: [],

        // enables/disables stage sorting
        allowStageSort: true,

        // append/prepend non-editable content to the form.
        append: false,
        prepend: false,

        // control order
        controlOrder: [
            "header",
            "text",
            "file",
            "select",
            "checkbox",
            "checkbox-group",
            "radio-group",
            "autocomplete",
            "paragraph",
            "number",
            "textarea",
            // '<a href="https://www.jqueryscript.net/time-clock/">date</a>',
            "button",
            "hidden",
        ],

        disableFields: [
            "autocomplete",
            "header",
            "file",
            "paragraph",
            "textarea",
            "date",
            "hidden",
            "number",
        ],

        container: true,

        // or left
        controlPosition: "right",

        // or 'xml'
        dataType: "json",

        formData: formJsonHolder.val(),

        // default fields
        defaultFields: [],

        // save, data, clear
        disabledActionButtons: ["data", "save", "clear"],

        // disabled attributes
        disabledAttrs: [],

        // disabled buttons
        disabledFieldButtons: {},

        // disabled subtypes
        disabledSubtypes: {},

        // disables html in field labels
        disableHTMLLabels: false,

        // removes the injected style
        disableInjectedStyle: false,

        // opens the edit panel on added field
        editOnAdd: false,

        // adds custom control configs
        fields: [],

        // warns user if before the remove a field from the stage
        fieldRemoveWarn: false,

        // DOM node or selector
        fieldEditContainer: null,

        // add groups of fields at a time
        inputSets: [],

        // custom notifications
        notify: {
            error: console.error,
            success: console.log,
            warning: console.warn,
        },

        // prevent clearAll from remove default fields
        persistDefaultFields: false,

        // replaces an existing field by id.
        replaceFields: [],

        // user roles
        roles: {
            1: "Administrator",
        },

        // smoothly scrolls to a field when its added to the stage
        scrollToFieldOnAdd: false,

        // shows action buttons
        showActionButtons: true,

        // sortable controls
        sortableControls: false,

        // sticky controls
        stickyControls: {
            enable: true,
            offset: {
                top: 5,
                bottom: "auto",
                right: "auto",
            },
        },

        // defines new types to be used with field base types such as button and input
        subtypes: {},

        // defines a custom output for new or existing fields.
        templates: {},

        // defines custom attributes for field types
        typeUserAttrs: {},

        // disabled attributes for specific field types
        typeUserDisabledAttrs: {},

        // adds functionality to existing and custom attributes using onclone and onadd events. Events return JavaScript DOM elements.
        typeUserEvents: {},
    });
}

function createForm() {
    if (!validateForm()) return false;
    if (!validateFormDates()) return false;
    let formJson = formBuilderRef.formData;
    if (JSON.parse(formJson).length === 0) {
        showAlert();
        return false;
    }
    $("#form_json").val(formJson);
    return true;
}

function updateForm() {
    if (!validateForm()) return false;
    if (!validateFormDates()) return false;
    let formJson = formUpdaterRef.formData;
    if (JSON.parse(formJson).length === 0) {
        showAlert();
        return false;
    }
    $("#form_json").val(formJson);
    return true;
}

function validateFormDates() {
    let start_date = $("#start_date"),
        end_date = $("#end_date"),
        moment_start_date_ref = moment(start_date.val()),
        moment_end_date_ref = moment(end_date.val());

    if (!moment_start_date_ref._isValid) {
        start_date.after(
            "<div class='error'>start date is either empty or invalid.</div>"
        );
        start_date.focus();
        return false;
    }

    if (!moment_end_date_ref._isValid) {
        end_date.after(
            "<div class='error'>end date is either empty or invalid.</div>"
        );
        end_date.focus();
        return false;
    }

    if (!moment_end_date_ref.isAfter(moment_start_date_ref)) {
        end_date.after(
            "<div class='error'>end date should be after start end.</div>"
        );
        end_date.focus();
        return false;
    }

    return true;
}


function validateForm() {
    let form_name = $("#form_name");
    $("#create_forms_form").find(".error").remove();
    if (form_name.val() === "") {
        form_name.after("<div class='error'>form name is required.</div>");
        form_name.focus();
        return false;
    }
    if (form_name.val().length < 2) {
        form_name.after(
            "<div class='error'>form's name's mininum length is 2.</div>"
        );
        form_name.focus();
        return false;
    }

    return true;
}

function showAlert() {
    Swal.fire({
        text: "Form with no fields can't be created.",
        icon: "error",
        customClass: {
            confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
    });
}

$(document).ready(function () {
    getProductOfComp(true);
});

function getProductOfComp(isInitialLoad = false) {
    let company_selected = $("#company_selected"),
        productSelectBox = $("#product_id"),
        selected_prod_hidden = $("#selected_prod_hidden"),
        productsHtml = "",
        id = null,
        prod_name = "";

    $.ajax({
        url: `${
            baseurl
        }/getprodofcomp/${company_selected.val()}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
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
        })
        .fail(function (err) {
            console.log(err);
        });
}
