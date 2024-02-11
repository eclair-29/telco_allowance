function getPlanFeeById(id, planFeeId) {
    $.ajax({
        type: "GET",
        url: `${baseUrl}/publisher/get_plan_fee?id=${id}`,
        success: function (response) {
            planFeeId.val(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

// Change value of plan_fee field when plan select is changed - for update action
$("body").on("change", ".plan-select", function () {
    getPlanFeeById(
        $(this).val(),
        $(`#plan_fee_${$(this).attr("id").replace("plan_", "")}`)
    );
});

// Change value of plan_fee field when plan select is changed - for add action
$("#add_assignee_plan").on("change", function () {
    getPlanFeeById($(this).val(), $("#add_assignee_plan_fee"));
});

function associateErrors(errors, fields) {
    const getMessage = (fieldErrors, field) =>
        fieldErrors.forEach((error) => {
            field.addClass("is-invalid");
            field
                .closest('[class*="col"]')
                .append(
                    '<span class="invalid-feedback fw-bold d-block">' +
                        error +
                        "</span>"
                );
        });

    if (errors.assignee) getMessage(errors.assignee, fields.assignee);
    if (errors.assignee_code)
        getMessage(errors.assignee_code, fields.assignee_code);
    if (errors.plan) getMessage(errors.plan, fields.plan);
    if (errors.position) getMessage(errors.position, fields.position);

    if (errors.account_no) getMessage(errors.account_no, fields.account_no);
    if (errors.phone_no) getMessage(errors.phone_no, fields.phone_no);
    if (errors.allowance) getMessage(errors.allowance, fields.allowance);
}

function clearErrorMsg(fields) {
    Object.values(fields).forEach((field) => {
        field.attr("class", field.attr("class").replace(" is-invalid", ""));

        const invalidFeedback = field
            .closest('[class*="col"]')
            .find(".invalid-feedback");

        const hasFeedback = invalidFeedback.length > 0;

        hasFeedback && invalidFeedback.remove();
    });
}

function clearAlert(alert) {
    alert.attr("hidden", true);
    alert.attr("class", "alert").text("");
}

/* Validation process - Update action */
$("body").on("submit", ".update-assignee", function (e) {
    e.preventDefault();

    const action = $(this).attr("action");
    const id = $(this).attr("id").replace("update_assignee_fields_", "");

    const fields = {
        account_no: $(`#account_no_${id}`),
        phone_no: $(`#phone_no_${id}`),
        allowance: $(`#allowance_${id}`),
    };

    clearErrorMsg(fields);

    const alert = $("#assignees").find(".alert");
    const updateModal = bootstrap.Modal.getOrCreateInstance(
        `#${$(this).closest(".popup").attr("id")}`
    );

    clearAlert(alert);

    $.ajax({
        url: action,
        type: "PUT",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $(this).serialize(),
        success: function (data) {
            alert.attr("hidden", false);
            if (data.response === "success")
                alert.attr("class", "alert alert-success").text(data.alert);
            else alert.attr("class", "alert alert-danger").text(data.alert);

            updateModal.hide();
        },
        error: function (error) {
            associateErrors(error.responseJSON.errors, fields);
        },
    });
});

/* Validation process - Add action */
$("#add_assignee_fields").on("submit", function (e) {
    e.preventDefault();

    const action = $(this).attr("action");

    const fields = {
        assignee: $("#assignee"),
        assignee_code: $("#assignee_code"),
        account_no: $("#account_no"),
        phone_no: $("#phone_no"),
        allowance: $("#allowance"),
        position: $("#position"),
        plan: $("#add_assignee_plan"),
    };

    clearErrorMsg(fields);

    const alert = $("#assignees").find(".alert");
    const updateModal = bootstrap.Modal.getOrCreateInstance(
        `#${$(this).closest(".popup").attr("id")}`
    );

    clearAlert(alert);

    $.ajax({
        url: action,
        type: "POST",
        data: $(this).serialize(),
        success: function (data) {
            alert.attr("hidden", false);
            if (data.response === "success")
                alert.attr("class", "alert alert-success").text(data.alert);
            else alert.attr("class", "alert alert-danger").text(data.alert);

            updateModal.hide();
            $("#add_assignee_fields").trigger("reset");
        },
        error: function (error) {
            associateErrors(error.responseJSON.errors, fields);
        },
    });
});
