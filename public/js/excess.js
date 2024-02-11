function generateWorksheet() {
    $.ajax({
        type: "GET",
        url: `${baseUrl}/publisher/generate`,
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

$("#generate_worksheet_btn").on("click", function () {
    generateWorksheet();
});
