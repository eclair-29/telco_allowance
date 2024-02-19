const excessTableCols = [
    { data: "account_no" },
    { data: "phone_no" },
    { data: "assignee_code" },
    { data: "assignee" },
    { data: "position" },
    { data: "allowance" },
    { data: "plan_fee" },
    { data: "pro_rated_bill" },
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

function totalByAttribute(attribute) {
    const excesses = excess_table.rows().data().toArray();
    const reduced = excesses.reduce((acc, obj) => acc + obj[attribute], 0);
    $("#total_" + attribute).text(parseFloatFixed(reduced));
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

                const handleCellEvent = (e) => {
                    if (original !== e.target.textContent) {
                        const row = excess_table.row($(e.target).parent());

                        const pro_rated_bill = e.target.textContent
                            ? parseFloatFixed(e.target.textContent)
                            : null;

                        const excess_balance_vat = parseFloatFixed(
                            (row.data().plan_fee +
                                row.data().excess_balance +
                                pro_rated_bill) *
                                0.12
                        );

                        const total_bill = parseFloatFixed(
                            row.data().plan_fee +
                                pro_rated_bill +
                                excess_balance_vat +
                                row.data().excess_balance +
                                row.data().loan_fee +
                                row.data().excess_charges_vat +
                                row.data().excess_charges +
                                row.data().non_vattable
                        );

                        excess_table
                            .row($(e.target).parent())
                            .data({
                                ...row.data(),
                                pro_rated_bill,
                                excess_balance_vat,
                                total_bill,
                            })
                            .draw(false);
                    }
                };

                // $(cell).on("blur", function (e) {
                //     handleCellEvent(e);
                // });

                $(cell).on("keydown", function (e) {
                    if (e.key === "Enter") {
                        handleCellEvent(e);

                        totalByAttribute("pro_rated_bill");
                        totalByAttribute("total_bill");
                        totalByAttribute("excess_balance_vat");
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

                        const allowanceMinusPlanFee = parseFloatFixed(
                            row.data().allowance - row.data().plan_fee
                        );

                        const excess_balance =
                            parseFloatFixed(e.target.textContent) <
                            allowanceMinusPlanFee
                                ? parseFloat(e.target.textContent)
                                : allowanceMinusPlanFee;

                        const excess_balance_vat = parseFloatFixed(
                            (row.data().plan_fee +
                                excess_balance +
                                row.data().pro_rated_bill) *
                                0.12
                        );

                        const excess_charges =
                            parseFloatFixed(e.target.textContent) >
                            allowanceMinusPlanFee
                                ? parseFloatFixed(
                                      e.target.textContent -
                                          allowanceMinusPlanFee
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
                                row.data().pro_rated_bill,
                            row.data().non_vattable
                        );

                        const deduction = parseFloatFixed(
                            row.data().loan_fee +
                                excess_charges_vat +
                                excess_charges +
                                row.data().non_vattable
                        );

                        excess_table
                            .row($(e.target).parent())
                            .data({
                                ...row.data(),
                                excess_balance,
                                excess_charges,
                                excess_balance_vat,
                                excess_charges_vat,
                                total_bill,
                                deduction,
                            })
                            .draw(false);

                        totalByAttribute("non_vattable");
                        totalByAttribute("deduction");
                        totalByAttribute("pro_rated_bill");
                        totalByAttribute("excess_balance");
                        totalByAttribute("excess_charges");
                        totalByAttribute("excess_balance_vat");
                        totalByAttribute("excess_charges_vat");
                        totalByAttribute("total_bill");
                    }
                };

                // $(cell).on("blur", function (e) {
                //     handleCellEvent(e);
                // });

                $(cell).on("keydown", function (e) {
                    if (e.key === "Enter") {
                        handleCellEvent(e);
                    }
                });
            },
            targets: 12,
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
            targets: 17,
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

let publisher_total_table = new DataTable("#publisher_total_table", {
    searching: false,
    paging: false,
    info: false,
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
overrideTable("publisher_total");
