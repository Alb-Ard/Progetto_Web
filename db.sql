CREATE TABLE `users` (
  `email` varchar(64) NOT NULL,
  `password` char(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);
COMMIT;
