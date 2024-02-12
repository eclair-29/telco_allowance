function getPlanFeeById(id, selectId) {
    $.ajax({
        type: "GET",
        url: `${baseUrl}/publisher/get_plan_fee?id=${id}`,
        success: function (response) {
            $(`#plan_fee_${selectId.replace("plan_", "")}`).val(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

$("body").on("change", ".plan-select", function () {
    getPlanFeeById($(this).val(), $(this).attr("id"));
});
