const excessTableCols = [];

let assignees_table = new DataTable("#assignees_table", {
    pageLength: 10,
});

let loans_table = new DataTable("#loans_table", {
    pageLength: 10,
});

let plans_table = new DataTable("#plans_table");

let excess_table = new DataTable("#excess_table", {
    data: [],
});

function overrideTable(id) {
    const entries = $(`#${id}_table_length`);
    const search = $(`#${id}_table_filter`);
    const top_controls =
        "<div class='table-top-controls d-flex align-items-center justify-content-between'></div>";

    $(`#${id}_table`).wrap("<div class='table-responsive'></div>");
    entries.wrap(top_controls);
    search.detach().appendTo(".table-top-controls");
}

overrideTable("assignees");
overrideTable("plans");
overrideTable("loans");
overrideTable("excess");
