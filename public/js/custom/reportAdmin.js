let allRecords = [];
const citiesSelectBox = $("#citiesSelectBox"),
    areasSelectBox = $("#areasSelectBox");

if (citiesSelectBox.length)
    citiesSelectBox.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
            dropdownAutoWidth: true,
            width: "100%",
            dropdownParent: $this.parent(),
        });
    });

if (areasSelectBox.length)
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
    let areasHtml = "",
        id = null,
        area_name = "";

    $.ajax({
        url: `${baseurl}/getareas/${city_id}`,
    })
        .done(function (data) {
            data = JSON.parse(data)?.data ?? [];
            if (data.length === 0)
                areasHtml += `<option value="0">No areas found</option>`;
            else {
                areasHtml += `<option value="0">Select area</option>`;
                data.forEach((area) => {
                    id = area.id;
                    area_name = area.area_name;
                    areasHtml += `<option value="${id}">${area_name}</option>`;
                });
            }
            areasSelectBox.html(areasHtml);
        })
        .fail(function (err) {
            console.log(err);
        });
}

function getReport(isAreaChanged = false) {
    let htmlHolderCont = $("#surveyResult"),
        city_id = citiesSelectBox.val(),
        area_id = areasSelectBox.val();

    if (isAreaChanged !== true) getAreas(city_id);

    $.ajax({
        url: `${baseurl}/getreportadmin/${formId}`,
        type: "post",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            ...(parseInt(city_id) && { city_id }),
            ...(parseInt(area_id) && { area_id }),
        },
    })
        .done(function (data) {
            reports = JSON.parse(data)?.data ?? [];
            allRecords = reports.map((report) => {
                return JSON.parse(report.data_filled);
            });
            renderResults();
        })
        .fail(function (err) {
            htmlHolderCont.html(`
        <div class="report_item card p-2">
            <div class="alert alert-danger mb-0 p-1">
            ${err?.responseJSON?.messsage ?? "Something went wrong"}
        </div>
        </div>`);
        });
}

$(document).ready(function () {
    getReport();
});

function renderResults() {
    let htmlHolderCont = $("#surveyResult"),
        firstRecored = allRecords[0] ?? [],
        values,
        optionsHtml = "",
        outerMost,
        inner,
        isInputTypeQues;

    if (firstRecored.length === 0) {
        htmlHolderCont.html(`
        <div class="report_item card p-2">
            Looks like no one has filled the form yet.
        </div>`);
        return;
    }

    htmlHolderCont.html(`
    <div class="report_item card p-2 mb-0 fw-bold d-flex justify-content-between flex-wrap flex-row align-items-center">
    <span>
        ${allRecords.length} ${
        allRecords.length > 1 ? "users" : "user"
    } have filled the form till now.
    </span>
    <button type="submit" class="btn btn-primary" onclick="exportToPdf()">Export to pdf</button>
    </div>.
    `);

    outerMost = [];
    firstRecored.map((singleQues, sqIndex) => {
        values = singleQues?.values ?? [];
        optionsHtml = "";
        if (values.length) {
            inner = [];
            values.map((option, oIndex) => {
                inner = [
                    ...inner,
                    {
                        id: `ques${sqIndex + 1}-op${oIndex + 1}`,
                        result: 0,
                    },
                ];
                optionsHtml += `
                <div class="answer_cont col-12 col-md-3 mb-1 mb-md-0" id="${inner[oIndex].id}">
                    <div class="ansVal">${option.label}</div>
                    <div class="ansResults">Loading</div>
                    <div id="progbar${inner[oIndex].id}"></div>
                </div>`;
            });
            outerMost = [
                ...outerMost,
                {
                    id: `ques${sqIndex + 1}`,
                    result: [...inner],
                },
            ];
        } else outerMost = [...outerMost, {}];

        isInputTypeQues = values.length === 0;

        htmlHolderCont.append(`
                <div class="report_item card p-2" id="${
                    outerMost[sqIndex]?.id ?? `ques${sqIndex + 1}`
                }">
                    <div class="report_ques">
                        ${singleQues.label}
                    </div>
                    <div class="report_ques_ans row mx-0">
                        ${
                            !isInputTypeQues
                                ? optionsHtml
                                : `<div class="pl-0" id="inputTypeQues${
                                      sqIndex + 1
                                  }"></div>`
                        }
                    </div>
                </div>`);
    });

    allRecords.forEach((singleRecord) => {
        singleRecord.forEach((singleQuestion, sqIndex) => {
            if (singleQuestion?.values?.length) {
                let timesToLoop = singleQuestion?.userData ?? [];
                timesToLoop.forEach((currAns) => {
                    singleQuestion?.values.forEach((currOption, coIndex) => {
                        if (currAns == currOption.value) {
                            outerMost[sqIndex].result[coIndex].result =
                                outerMost[sqIndex].result[coIndex].result + 1;
                        }
                    });
                });
            } else {
                let html = $(`#inputTypeQues${sqIndex + 1}`).html(),
                    htmlToAdd = `
                    <div>${singleQuestion?.userData[0]}</div>
                `;
                html += htmlToAdd;
                $(`#inputTypeQues${sqIndex + 1}`).html(html);
            }
        });
    });

    let totalUsersFilledTheForm = allRecords.length,
        chartConfig = {},
        colors = ["#0dcaf0", "#ff9f43", "#6610f2", "#28c76f", "#ea5455"];
    outerMost = outerMost.map((currQues) => {
        if (currQues?.result)
            return currQues.result.map((currOption, index) => {
                let totalPer =
                    (currOption.result * 100) / totalUsersFilledTheForm;
                totalPer = totalPer.toFixed(1);
                $(`#${currOption.id} .ansResults`).text(`${totalPer}%`);

                chartConfig = {
                    chart: {
                        height: 25,
                        width: 25,
                        type: "radialBar",
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: -15,
                            right: -15,
                            top: -12,
                            bottom: -15,
                        },
                    },
                    colors: [colors[index] ?? colors[0]],
                    series: [totalPer],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "15%",
                            },
                            track: {
                                background: "#EBEBEB",
                            },
                            dataLabels: {
                                showOn: "always",
                                name: {
                                    show: false,
                                },
                                value: {
                                    show: false,
                                },
                            },
                        },
                    },
                    stroke: {
                        lineCap: "round",
                    },
                };
                chartInstance = new ApexCharts(
                    $(`#progbar${currOption.id}`).get(0),
                    chartConfig
                );
                chartInstance.render();
                return totalPer;
            });
    });
}

const exportToPdf = () => {
    const city_id = citiesSelectBox.val();
    const area_id = areasSelectBox.val();
    window.location.href = `${baseurl}/export/${formId}/${city_id}/${area_id}/0`;
    formId;
};
