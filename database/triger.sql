CREATE OR REPLACE FUNCTION init_hoursheet() RETURNS trigger AS $$
DECLARE
	start DATE;
	curr DATE;
	stop DATE;
	h_sum INTEGER;
	h_day INTEGER;
	y INTEGER;
	m INTEGER;
	cid INTEGER;
BEGIN
	DELETE FROM hours_per_month WHERE contract_id=cid;

	cid := NEW.contract_id;
	start := NEW.start_date;
	stop := NEW.end_date;
	h_day := NEW.hours_per_day;

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
			VALUES (cid,y,m,h_sum);
	END LOOP;
	RETURN NULL;
END;
$$  LANGUAGE plpgsql;