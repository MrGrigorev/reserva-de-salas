USE reservadesalas;

INSERT INTO reservations(id, start_time, end_time, room_id, nusp, description, is_activated) 
	VALUES (1, '2012-06-30 10:25:25', '2012-06-30 11:25:25', 1, '12345678', 'Super reserva', 1),
	(2, '2012-06-30 11:25:25', '2012-06-30 12:25:25', 2, '111111', 'Super reserva', 0);