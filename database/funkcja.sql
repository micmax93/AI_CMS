CREATE OR REPLACE FUNCTION init_hoursheet(contract_id INTEGER) RETURNS integer AS $$
DECLARE
	start DATE;
	curr DATE;
	stop DATE;
	h_sum INTEGER;
	h_day INTEGER;
	y INTEGER;
	m INTEGER;
BEGIN
	SELECT start_date, end_date, hours_per_day
	INTO start, stop, h_day
	FROM contract where id=contract_id;

	curr := start;
	WHILE curr <= stop LOOP
		y := EXTRACT(YEAR FROM curr);
		m := EXTRACT(MONTH FROM curr);
		h_sum := 0;
		WHILE m = EXTRACT(MONTH FROM curr) LOOP
			IF EXTRACT(ISODOW FROM curr) <= 5 THEN
				h_sum := h_sum + h_day;
			END IF;
			curr := curr +  + integer '1';
		END LOOP;
		INSERT INTO hours_per_month(contract_id,year,month,hours)
			VALUES (contract_id,y,m,h_sum);
	END LOOP;
	RETURN 0;
END;
$$  LANGUAGE plpgsql;