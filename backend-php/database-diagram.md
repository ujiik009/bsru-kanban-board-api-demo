# 🖥 Database Diagram

## Database Diagram

{% embed url="https://dbdiagram.io/d/61a06ef08c901501c0d374aa" %}

![](<../.gitbook/assets/image (49).png>)

### SQL File

{% file src="../.gitbook/assets/kanban_database (3).sql" %}

### SQL script

```sql
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

```

## Create Database Schema

1. ทำการเปิดโปรแกรม phpmyadmin จาก โปรแกรม xampp ขึ้นมา

![](<../.gitbook/assets/image (125).png>)

2\. ทำการ new database โดยใช้ชื่อว่า `kanban_project_db` และให้ทำการเลือก Collation utf8\_general\_ci จากนั้นกด ปุ่ม create

![](<../.gitbook/assets/image (47).png>)

จากนั้นทำการ run sql script เพื่อทำการ สร้าง table โดยการกดที่ database [`kanban_project_db`](http://localhost/phpmyadmin/index.php?route=/database/structure\&server=1\&db=kanban\_project\_db)และทำการกดไปที่ tab `SQL`

![](<../.gitbook/assets/image (81).png>)

![](<../.gitbook/assets/image (24).png>)

จากนั้นทำการ copy SQL script [#sql-script](database-diagram.md#sql-script "mention") ที่จัดเตรียมไว้ให้นำไปวาง จากนั้นกดปุ่ม GO

![](<../.gitbook/assets/image (17).png>)

จากนั้น โปรแกรมจะทำการสร้าง table ดังรูปด้านล่าง

![](<../.gitbook/assets/image (15).png>)
