

INSERT INTO users (name, email, password) VALUES
('Артем', 'artem@gmail.com', md5('qwerty123')),
('Иван', 'ivan@bk.ru', md5('ytrewq321'));

INSERT INTO categories (name, user_id) VALUES
    ('Входящие', 2),
    ('Учеба', 1),
    ('Работа', 1),
    ('Домашние дела', 2),
    ('Авто', null);

INSERT INTO tasks (task, date_completion, categories_id, status, user_id) VALUES

('Собеседование в IT компании', '2021.02.02', 3, 0, 1),
('Выполнить тестовое задание', '2021.01.31', 3, 0, 1),
('Сделать задание первого раздела', '2021.01.15', 2, 1, 1),
('Встреча с другом', '2021.01.15', 1, 0, 2),
('Купить корм для кота', '2021.03.01', 4, 0, 2),
('Заказать пиццу', null, 4, 0, 2);

-- получить список из всех проектов для одного пользователя;
select c.name from categories c
inner join tasks t on c.id = t.categories_id
inner join users u on u.id = t.user_id and u.id = 1;

select categories.name, users.name from tasks
inner join categories on categories.id = tasks.categories_id
inner join users on users.id = tasks.user_id and users.id = 1

--получить список из всех задач для одного проекта;

SELECT * FROM tasks WHERE categories_id = 2;

--  пометить задачу как выполненную;
UPDATE tasks SET status = 1 WHERE id = 1;

-- обновить название задачи по её идентификатору.
UPDATE tasks SET task = 'Заказать еще одну пиццу' WHERE id = 1;
