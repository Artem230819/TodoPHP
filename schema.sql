create database сoursephp
default character set utf8
default collate utf8_general_ci;

use сoursephp;



create table users (
    id int auto_increment primary key,
    register_date TIMESTAMP default current_timestamp,
    name char(128) not null,
    email char(128) not null unique,
    password char(128) not null
);
CREATE INDEX register_date ON users(register_date);
CREATE INDEX password ON users(password);

create table categories (
    id INT auto_increment primary key,
    name CHAR(128) NOT NULL,
    user_id INT,
    FOREIGN KEY(user_id) references users(id)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task CHAR(128) NOT NULL,
    date_completion TIMESTAMP default current_timestamp,
    date_time_add TIMESTAMP default current_timestamp,
    status TINYINT NOT NULL default 0,
    file CHAR(255) default null,
    categories_id INT NOT NULL,
    user_id int not null,
    FOREIGN KEY(categories_id) references categories(id),
    FOREIGN KEY(user_id) references users(id)
);
CREATE INDEX status ON tasks(status);
CREATE INDEX date_completion_date_time_add ON tasks(date_completion, date_time_add);
CREATE FULLTEXT INDEX fulltxt on tasks(task);
