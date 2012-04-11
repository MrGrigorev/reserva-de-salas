USE cadastrodepessoas;

INSERT INTO users(nusp, name, password, activation_status, user_type, email) VALUES
('12345678', 'admin', '5d4ec527e929f6b5d1715451f7b3d37f4da56326', 'active', 'admin', 'admin@gmail.com'),
('111111', 'user1', '5d4ec527e929f6b5d1715451f7b3d37f4da56326', 'waiting_activation', 'user', 'user1@gmail.com'),
('222222', 'user2', '5d4ec527e929f6b5d1715451f7b3d37f4da56326', 'waiting_activation', 'user', 'user2@gmail.com'),
('333333', 'user3', '5d4ec527e929f6b5d1715451f7b3d37f4da56326', 'waiting_activation', 'user', 'user3@gmail.com');