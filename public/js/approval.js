$("body").on("click", ".approved-btn", function (event) {
    const notes = prompt("Add notes:", "Approved");

    const approveInput = $(
        `#approved_notes_${$(this).attr("id").replace("approved_btn_", "")}`
    ).attr("id");

    if ($(this) && notes) {
        $(`#${approveInput}`).val(notes);
    } else {
        event.preventDefault();
    }
});

$("body").on("click", ".rejected-btn", function (event) {
    const notes = prompt("Rejection Reason:", "");

    const rejectInput = $(
        `#rejected_notes_${$(this).attr("id").replace("rejected_btn_", "")}`
    ).attr("id");

    if ($(this) && notes) {
        $(`#${rejectInput}`).val(notes);
    } else {
        event.preventDefault();
    }
});
