CREATE VIEW daily_summary AS
((SELECT
  user_id, contract_id,
  end_hour-start_hour as duration,
  year,
  month,
  day,
  week,
  'present' as type,
  description
FROM timesheet)
UNION
(
  SELECT
     (SELECT user_id FROM contract WHERE id=contract_id) as user_id,
     contract_id,
     (SELECT hours_per_day FROM contract WHERE id=contract_id) as duration,
     EXTRACT(YEAR FROM date) AS year,
     EXTRACT(MONTH FROM date) AS month,
     EXTRACT(DAY FROM date) AS day,
     EXTRACT(WEEK FROM date) AS day,
     type,
     descryption
  FROM days_off
));