function setControlBtnStatus(status) {
    $("#download_worksheet").attr("disabled", status);
    $("#save_worksheet").attr("disabled", status);
    $("#publish_worksheet").attr("disabled", status);
}

function getTotal(excesses, attribute) {
    const reduced = excesses.reduce((acc, obj) => acc + obj[attribute], 0);
    $("#total_" + attribute).text(parseFloatFixed(reduced));
}

const totals = (excesses) => {
    getTotal(excesses, "allowance");
    getTotal(excesses, "deduction");
    getTotal(excesses, "non_vattable");
    getTotal(excesses, "plan_fee");
    getTotal(excesses, "pro_rated_bill");
    getTotal(excesses, "excess_balance");
    getTotal(excesses, "excess_charges");
    getTotal(excesses, "excess_balance_vat");
    getTotal(excesses, "excess_charges_vat");
    getTotal(excesses, "total_bill");
};

function generateWorksheet() {
    $.ajax({
        type: "get",
        url: `${baseUrl}/publisher/generate`,
        success: function (response) {
            if (response.data) {
                excess_table.clear().draw();
                excess_table.rows.add(response.data).draw();

                totals(response.data);
                setControlBtnStatus(false);
            } else {
                alert(response.alert);
            }
        },
        error: function (error) {
            console.log(error);
            alert(error.alert);
        },
    });
}

function getExcessesBySeries(series_id) {
    $.ajax({
        type: "get",
        url: `${baseUrl}/publisher/get_excesses?series_id=${series_id}`,
        success: function (response) {
            excess_table.clear().draw();
            excess_table.rows.add(response.data).draw();

            totals(response.data);

            $("#download_worksheet").attr(
                "href",
                `${baseUrl}/publisher/download?series_id=${series_id}`
            );

            $("#status_header").text("Status: ");
            $("#status_header").append(
                "<span>" + response.data[0].status + "</span>"
            );

            if (response.data[0].status === "draft")
                $("#status_header span").css("color", "#856404");

            if (response.data[0].status === "for approval")
                $("#status_header span").css("color", "#084298");

            if (response.data[0].status === "rejected")
                $("#status_header span").css("color", "#842029");

            if (response.data[0].status === "published")
                $("#status_header span").css("color", "#155724");
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function getWorksheetAction(action, notes) {
    const tableRows = excess_table.rows().data().toArray();

    $.ajax({
        type: "post",
        url: `${baseUrl}/publisher/${action}`,
        data: {
            excesses: JSON.stringify(tableRows),
            // notes,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (result) {
            if (alert(result.alert)) location.reload();
        },
        error: function (error) {
            alert(error.alert);
        },
    });
}

setControlBtnStatus(true);

$("#generate_worksheet_btn").on("click", function () {
    generateWorksheet();
});

$("#series_select").on("change", function () {
    getExcessesBySeries($(this).val());

    setControlBtnStatus(false);

    $("#series_header").text(
        $(this).find(":selected").text() + " Telco Rundown"
    );

    $("#publisher_total").attr("hidden", false);
});

$("#save_worksheet").on("click", function () {
    getWorksheetAction("save", "");
});

$("#publish_worksheet").on("click", function () {
    const notes = prompt("Add notes:", "Request for approval");

    if (notes) {
        getWorksheetAction("publish", notes);
    }
});

$("#download_worksheet").on("click", function () {
    document.getElementById("download_worksheet").click();
});
