@props(['view','excesses'])

<x-table id="{{$view}}total_table">
    <thead>
        <tr>
            <th id="excesses_total_cell" colspan="11" class="text-center fw-bold">Total Expenses</th>
        </tr>
        <tr>
            <th>Allowance</th>
            <th>Plan Fee</th>
            <th>Prorated Bill</th>
            <th>Excess Usage</th>
            <th>Usage VAT</th>
            <th>Loan Fee</th>
            <th>Excess Charges</th>
            <th>Charges VAT</th>
            <th>Non Vattable</th>
            <th>Total Bill</th>
            <th>Deduction</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td id="total_allowance">0</td>
            <td id="total_plan_fee">0</td>
            <td id="total_pro_rated_bill">0</td>
            <td id="total_excess_balance">0</td>
            <td id="total_excess_balance_vat">0</td>
            <td id="total_loan_fee">0</td>
            <td id="total_excess_charges">0</td>
            <td id="total_excess_charges_vat">0</td>
            <td id="total_non_vattable">0</td>
            <td id="total_total_bill">0</td>
            <td id="total_deduction">0</td>
        </tr>
    </tbody>
</x-table>