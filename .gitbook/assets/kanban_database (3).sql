CREATE TABLE `users` (
  `id` varchar(64) PRIMARY KEY,
  `email` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255),
  `position` varchar(255),
  `bio` varchar(255)
);

CREATE TABLE `projects` (
  `id` varchar(64) PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `description` varchar(255),
  `start_date` varchar(255),
  `end_date` varchar(255),
  `created_at` varchar(255),
  `creator` varchar(64)
);

CREATE TABLE `tasks` (
  `id` varchar(64) PRIMARY KEY,
  `project_id` varchar(64),
  `name` varchar(255),
  `description` varchar(255),
  `state` ENUM ('todo', 'in_progress', 'done'),
  `assign_to` varchar(64),
  `color` varchar(255),
  `due_date` varchar(255)
);

CREATE TABLE `comments` (
  `id` varchar(64) PRIMARY KEY,
  `comment` varchar(255),
  `task_id` varchar(64),
  `commentator` varchar(64)
);

CREATE TABLE `project_user` (
  `id` varchar(64) PRIMARY KEY,
  `project_id` varchar(64),
  `user_id` varchar(64)
);

ALTER TABLE `projects` ADD CONSTRAINT `creator` FOREIGN KEY (`creator`) REFERENCES `users` (`id`);

ALTER TABLE `tasks` ADD CONSTRAINT `task_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `tasks` ADD CONSTRAINT `assign_to` FOREIGN KEY (`assign_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `comments` ADD CONSTRAINT `task_id` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `comments` ADD CONSTRAINT `commentator` FOREIGN KEY (`commentator`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `project_user` ADD CONSTRAINT `user_project_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `project_user` ADD CONSTRAINT `user_project_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
