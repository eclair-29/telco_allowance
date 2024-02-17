function setControlBtnStatus(status) {
    $("#download_worksheet").attr("disabled", status);
    $("#save_worksheet").attr("disabled", status);
    $("#publish_worksheet").attr("disabled", status);
}

function generateWorksheet() {
    $.ajax({
        type: "get",
        url: `${baseUrl}/publisher/generate`,
        success: function (response) {
            if (response.data) {
                excess_table.clear().draw();
                excess_table.rows.add(response.data).draw();
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
            notes,
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
