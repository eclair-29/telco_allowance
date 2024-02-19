create view telco_allowance_db.vw_consolidated_excesses as 
SELECT 
    a.account_no,
    a.phone_no,
    a.assignee_code,
    a.assignee,
    pos.description as 'position',
    a.allowance,
    plan.subscription_fee as 'plan_fee',
    e.pro_rated_bill,
    e.excess_balance,
    e.excess_balance_vat,
    if(
        l.status_id = (select id FROM statuses where description = 'finished'), 
            null, 
            concat(l.current_subscription_count, ' / ' , l.total_subscription_count)
    ) as loan_progress,
    if(l.status_id = (select id FROM statuses where description = 'finished'), null, l.subscription_fee) as 'loan_fee',
    e.excess_charges,
    e.excess_charges_vat,
    e.non_vattable,
    e.total_bill,
    e.deduction,
    e.notes,
    s.description as 'status',
    e.series_id,
    e.assignee_excess_id
FROM excesses e 
    LEFT JOIN assignees a ON e.assignee_id = a.id
    LEFT JOIN positions pos ON a.position_id = pos.id
    LEFT JOIN plans plan ON a.plan_id = plan.id
    LEFT JOIN loans l ON a.id = l.assignee_id
    LEFT JOIN statuses s ON e.status_id = s.id;
-- WHERE e.series_id = ?;