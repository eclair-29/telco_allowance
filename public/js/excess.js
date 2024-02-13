function generateWorksheet() {
    $.ajax({
        type: "GET",
        url: `${baseUrl}/publisher/generate`,
        success: function (response) {
            if (response.data) {
                excess_table.clear().draw();
                excess_table.rows.add(response.data).draw();
            } else {
                alert(response.alert);
            }
            console.log(response);
        },
        error: function (error) {
            console.log(error);
            alert(error.alert);
        },
    });
}

function getExcessesBySeries(series_id) {
    $.ajax({
        type: "GET",
        url: `${baseUrl}/publisher/get_excesses?series_id=${series_id}`,
        success: function (response) {
            excess_table.clear().draw();
            excess_table.rows.add(response.data).draw();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

$("#generate_worksheet_btn").on("click", function () {
    generateWorksheet();
});

$("#series_select").on("change", function () {
    getExcessesBySeries($(this).val());
});

$("#save_worksheet").on("click", function () {
    console.log("data for saving", excess_table.data());
});
