INSERT INTO users (id, name, email, password, role, created_at, updated_at) VALUES
(null, 'Student One', 'student1@example.com', '12345678', 'student', null, null),
(null, 'Student Two', 'student2@example.com', '12345678', 'student', null, null),
(null, 'Student Three', 'student3@example.com', '12345678', 'student', null, null),
(null, 'Student Four', 'student4@example.com', '12345678', 'student', null, null),
(null, 'Student Five', 'student5@example.com', '12345678', 'student', null, null);

INSERT INTO reservations (dateReservation, timeSlot, pc, user_id, created_at, updated_at) VALUES
('2025-01-10', '0', 'pc1', 1, null, null),
('2025-01-10', '1', 'pc2', 2, null, null),
('2025-01-10', '2', 'pc3', 3, null, null),
('2025-01-10', '3', 'pc4', 4, null, null),
('2025-01-11', '0', 'pc5', 5, null, null),
('2025-01-11', '1', 'pc1', 1, null, null),
('2025-01-11', '2', 'pc2', 2, null, null),
('2025-01-11', '3', 'pc3', 3, null, null),
('2025-01-12', '0', 'pc4', 4, null, null),
('2025-01-12', '1', 'pc5', 5, null, null),
('2025-01-12', '2', 'pc1', 1, null, null),
('2025-01-12', '3', 'pc2', 2, null, null),
('2025-01-13', '0', 'pc3', 3, null, null),
('2025-01-13', '1', 'pc4', 4, null, null),
('2025-01-13', '2', 'pc5', 5, null, null);
