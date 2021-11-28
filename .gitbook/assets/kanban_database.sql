CREATE TABLE `users` (
  `id` uuid PRIMARY KEY,
  `email` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255),
  `position` varchar(255),
  `bio` varchar(255)
);

CREATE TABLE `projects` (
  `id` uuid PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `description` varchar(255),
  `start_date` varchar(255),
  `end_date` varchar(255),
  `created_at` varchar(255),
  `creator` uuid
);

CREATE TABLE `tasks` (
  `id` uuid PRIMARY KEY,
  `project_id` uuid,
  `name` varchar(255),
  `description` varchar(255),
  `state` ENUM ('todo', 'in_progress', 'done'),
  `assign_to` uuid,
  `color` varchar(255),
  `due_date` varchar(255)
);

CREATE TABLE `comments` (
  `id` uuid PRIMARY KEY,
  `comment` varchar(255),
  `task_id` uuid,
  `commentator` uuid
);

CREATE TABLE `project_user` (
  `id` uuid PRIMARY KEY,
  `project_id` uuid,
  `user_id` uuid
);

ALTER TABLE `projects` ADD FOREIGN KEY (`creator`) REFERENCES `users` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`assign_to`) REFERENCES `users` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`commentator`) REFERENCES `users` (`id`);

ALTER TABLE `project_user` ADD FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

ALTER TABLE `project_user` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
