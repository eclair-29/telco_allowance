function getAssigneeInfoById(id, code, position) {
    $.ajax({
        type: "GET",
        url: `${baseUrl}/publisher/assignees/get_assignee?id=${id}`,
        success: function (response) {
            code.val(response.assignee_code);
            position.val(response.position.description);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

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
    if (errors.current_subscription_count)
        getMessage(
            errors.current_subscription_count,
            fields.current_subscription_count
        );
    if (errors.total_subscription_count)
        getMessage(
            errors.total_subscription_count,
            fields.total_subscription_count
        );
    if (errors.subscription_fee)
        getMessage(errors.subscription_fee, fields.subscription_fee);
    if (errors.status) getMessage(errors.status, fields.status);
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

// Change value of assignee code and position field when assignee select is changed - for update action
$("body").on("change", ".assignee-select", function () {
    getAssigneeInfoById(
        $(this).val(),
        $(
            `#loan_assignee_code_${$(this)
                .attr("id")
                .replace("loan_assignee_", "")}`
        ),
        $(
            `#loan_assignee_position_${$(this)
                .attr("id")
                .replace("loan_assignee_", "")}`
        )
    );
});

// Change value of assignee code and position field when assignee select is changed - for add action
$("#loan_assignee").on("change", function () {
    getAssigneeInfoById(
        $(this).val(),
        $("#loan_assignee_code"),
        $("#loan_assignee_position")
    );
});

/* Validation process - Update action */
$("body").on("submit", ".update-loan", function (e) {
    e.preventDefault();

    const action = $(this).attr("action");
    const id = $(this).attr("id").replace("update_loan_fields_", "");

    const fields = {
        current_subscription_count: $(`#current_subscription_count_${id}`),
        total_subscription_count: $(`#total_subscription_count_${id}`),
        subscription_fee: $(`#loan_subscription_fee_${id}`),
    };

    clearErrorMsg(fields);

    const alert = $("#loans").find(".alert");
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
$("#add_loan_fields").on("submit", function (e) {
    e.preventDefault();

    const action = $(this).attr("action");

    const fields = {
        assignee: $("#loan_assignee"),
        current_subscription_count: $("#current_subscription_count"),
        total_subscription_count: $("#total_subscription_count"),
        status: $("#loan_status"),
        subscription_fee: $("#loan_subscription_fee"),
    };

    clearErrorMsg(fields);

    const alert = $("#loans").find(".alert");
    const addModal = bootstrap.Modal.getOrCreateInstance(
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

            addModal.hide();
            $("#add_loan_fields").trigger("reset");
        },
        error: function (error) {
            associateErrors(error.responseJSON.errors, fields);
        },
    });
});
