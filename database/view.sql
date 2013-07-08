SELECT
	contract_id,
	(SELECT user_id FROM contract WHERE id=contract_id) AS user_id,
	year,
	month,
	COALESCE(duration,0) AS hours,
	hours AS todo
FROM hours_per_month LEFT JOIN
(SELECT hour_sum.user_id, hour_sum.year, hour_sum.month, 
    sum(hour_sum.duration) AS duration, hour_sum.contract_id
   FROM ( SELECT timesheet.contract_id, timesheet.user_id, timesheet.year, 
            timesheet.month, 
            timesheet.end_hour - timesheet.start_hour AS duration
           FROM timesheet) hour_sum
  GROUP BY hour_sum.contract_id, hour_sum.user_id, hour_sum.year, hour_sum.month
) AS m_sum
USING(contract_id,year,month)
;