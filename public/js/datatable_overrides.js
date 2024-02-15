const excessTableCols = [
    { data: "account_no" },
    { data: "phone_no" },
    { data: "assignee_code" },
    { data: "assignee" },
    { data: "position" },
    { data: "allowance" },
    { data: "plan_fee" },
    { data: "excess_balance" },
    { data: "excess_balance_vat" },
    { data: "loan_progress" },
    { data: "loan_fee" },
    { data: "excess_charges" },
    { data: "excess_charges_vat" },
    { data: "non_vattable" },
    { data: "total_bill" },
    { data: "deduction" },
    { data: "notes" },
];

function parseFloatFixed(string) {
    return parseFloat(parseFloat(string).toFixed(2));
}

function cellAtrribute(cell) {
    cell.setAttribute("contenteditable", true);
    cell.setAttribute("spellcheck", false);
}

function editExcessCell() {
    return [
        {
            createdCell: function (cell) {
                let original;

                cellAtrribute(cell);

                cell.addEventListener("focus", function (e) {
                    original = e.target.textContent;
                });

                $(cell).on("blur", function (e) {
                    if (original !== e.target.textContent) {
                        const row = excess_table.row($(e.target).parent());

                        const excess_balance = e.target.textContent
                            ? parseFloatFixed(e.target.textContent)
                            : null;

                        const excess_balance_vat = parseFloatFixed(
                            (row.data().plan_fee + excess_balance) * 0.12
                        );

                        const excess_charges =
                            row.data().excess_charges && excess_balance
                                ? parseFloatFixed(
                                      row.data().excess_charges - excess_balance
                                  )
                                : null;

                        const excess_charges_vat = parseFloatFixed(
                            (row.data().loan_fee + excess_charges) * 0.12
                        );

                        const total_bill = parseFloatFixed(
                            row.data().plan_fee +
                                excess_balance_vat +
                                excess_balance +
                                row.data().loan_fee +
                                excess_charges_vat +
                                excess_charges +
                                row.data().non_vattable
                        );

                        excess_table
                            .row($(e.target).parent())
                            .data({
                                ...row.data(),
                                excess_balance_vat,
                                excess_balance,
                                excess_charges_vat,
                                excess_charges,
                                total_bill,
                            })
                            .draw(false);

                        console.log("Row changed: ", {
                            ...row.data(),
                            excess_balance: parseFloat(e.target.textContent),
                        });
                    }
                });
            },
            targets: 7,
        },
        {
            createdCell: function (cell) {
                let original;

                cellAtrribute(cell);

                cell.addEventListener("focus", function (e) {
                    original = e.target.textContent;
                });

                const handleCellEvent = (e) => {
                    if (original !== e.target.textContent) {
                        const row = excess_table.row($(e.target).parent());

                        const excess_charges = e.target.textContent
                            ? parseFloatFixed(
                                  e.target.textContent -
                                      row.data().excess_balance
                              )
                            : null;

                        const excess_charges_vat = parseFloatFixed(
                            (row.data().loan_fee + excess_charges) * 0.12
                        );

                        const total_bill = parseFloatFixed(
                            row.data().plan_fee +
                                row.data().excess_balance_vat +
                                row.data().excess_balance +
                                row.data().loan_fee +
                                excess_charges_vat +
                                excess_charges +
                                row.data().non_vattable
                        );

                        const deduction = parseFloatFixed(
                            row.data().loan_fee +
                                excess_charges_vat +
                                excess_charges
                        );

                        excess_table
                            .row($(e.target).parent())
                            .data({
                                ...row.data(),
                                excess_charges,
                                excess_charges_vat,
                                total_bill,
                                deduction,
                            })
                            .draw(false);

                        console.log("Row changed: ", {
                            ...row.data(),
                            excess_balance: parseFloat(e.target.textContent),
                        });
                    }
                };

                $(cell).on("blur", function (e) {
                    handleCellEvent(e);
                });

                $(cell).on("keydown", function (e) {
                    if (e.key === "Enter") {
                        handleCellEvent(e);
                    }
                });
            },
            targets: 11,
        },
        {
            createdCell: function (cell) {
                let original;

                cellAtrribute(cell);

                cell.addEventListener("focus", function (e) {
                    original = e.target.textContent;
                });

                $(cell).on("blur", function (e) {
                    if (original !== e.target.textContent) {
                        const row = excess_table.row($(e.target).parent());

                        excess_table
                            .row($(e.target).parent())
                            .data({
                                ...row.data(),
                                notes: e.target.textContent,
                            })
                            .draw(false);
                    }
                });
            },
            targets: 16,
        },
    ];
}

let tickets_table = new DataTable("#tickets_table", {
    order: [
        [3, "asc"],
        [5, "desc"],
    ],
});

let assignees_table = new DataTable("#assignees_table", {
    pageLength: 10,
});

let loans_table = new DataTable("#loans_table", {
    pageLength: 10,
});

let plans_table = new DataTable("#plans_table");

let excess_table = new DataTable("#excess_table", {
    data: [],
    columnDefs: editExcessCell(),
    columns: excessTableCols,
});

function overrideTable(id) {
    const entries = $(`#${id}_table_length`);
    const search = $(`#${id}_table_filter`);
    const top_controls =
        "<div class='table-top-controls d-flex align-items-center justify-content-between' id='" +
        id +
        "_table_top_controls'></div>";

    $(`#${id}_table`).wrap("<div class='table-responsive'></div>");

    entries.wrap(top_controls);
    search.detach().appendTo("#" + id + "_table_top_controls");
}

overrideTable("assignees");
overrideTable("plans");
overrideTable("loans");
overrideTable("excess");
overrideTable("tickets");
