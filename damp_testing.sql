CREATE TABLE testing_subject (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subjects_name VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('deleted', 'active') NOT NULL,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE testing_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classes_name VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('deleted', 'active') NOT NULL,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE testing_dialogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT,
    answer_correct INT,
    answer_0 TEXT,
    answer_1 TEXT,
    answer_2 TEXT,
    answer_3 TEXT,
    audio_url VARCHAR(255),
    image_url VARCHAR(255),
    subjects VARCHAR(255),
    classes VARCHAR(255),
    status ENUM('active', 'none', 'generated'),
    gid VARCHAR(255),
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO testing_subject (subjects_name, status) VALUES
    ('Химия', 'active'),
    ('Физика', 'active'),
    ('История', 'active'),
    ('Биология', 'active'),
    ('Естествознание', 'active');

INSERT INTO testing_classes (classes_name, status) VALUES
    ('5', 'active'),
    ('6', 'active'),
    ('7', 'active'),
    ('8', 'active'),
    ('9', 'active'),
    ('10', 'active'),
    ('11', 'active');

INSERT INTO testing_dialogs (question, answer_correct, answer_0, answer_1, answer_2, answer_3, audio_url, image_url, subjects, classes, status) VALUES
    ('В чем смысл Химии?', 0, 'Химия изучает строение веществ и их взаимодействия', '', '', '', 'default.wav', 'default.svg', 'Химия', '7', 'none'),
    ('Что такое химический элемент?', 0, 'Химический элемент - это вещество, состоящее из атомов с одинаковым количеством протонов в ядре', '', '', '', 'default.wav', 'default.svg', 'Химия', '7', 'none'),
    ('Какие существуют виды химических реакций?', 0, 'Существует много видов химических реакций, таких как синтез, анализ, окисление, восстановление и др.', '', '', '', 'default.wav', 'default.svg', 'Химия', '7', 'none'),
    ('Чем отличаются кислоты и основания?', 0, 'Кислоты имеют низкий pH, а основания - высокий pH. Кислоты могут отдавать протоны, а основания могут принимать протоны.', '', '', '', 'default.wav', 'default.svg', 'Химия', '7', 'none'),
    ('Какие элементы составляют периодическую таблицу?', 0, 'Периодическая таблица химических элементов включает в себя все химические элементы, упорядоченные по атомному номеру.', '', '', '', 'default.wav', 'default.svg', 'Химия', '7', 'none');

INSERT INTO testing_dialogs (question, answer_correct, answer_0, answer_1, answer_2, answer_3, audio_url, image_url, subjects, classes, status) VALUES
    ('Что такое физика?', 0, 'Физика изучает природу и поведение материи и энергии во вселенной.', '', '', '', 'default.wav', 'default.svg', 'Физика', '7', 'none'),
    ('Какие силы действуют в мире?', 0, 'Существует много видов сил, включая гравитацию, электромагнитные силы, силы ядра и другие.', '', '', '', 'default.wav', 'default.svg', 'Физика', '7', 'none'),
    ('Что такое механика?', 0, 'Механика - это раздел физики, который изучает движение и взаимодействие тел в пространстве и времени.', '', '', '', 'default.wav', 'default.svg', 'Физика', '7', 'none'),
    ('Что такое электричество и магнетизм?', 0, 'Электричество и магнетизм - это два важных аспекта физики, связанных с электрическими и магнитными полями.', '', '', '', 'default.wav', 'default.svg', 'Физика', '7', 'none'),
    ('Какие законы Ньютона?', 0, 'Законы Ньютона описывают движение тел и включают закон инерции, второй закон и закон взаимодействия.', '', '', '', 'default.wav', 'default.svg', 'Физика', '7', 'none');

INSERT INTO testing_dialogs (question, answer_correct, answer_0, answer_1, answer_2, answer_3, audio_url, image_url, subjects, classes, status) VALUES
    ('Что такое биология?', 0, 'Биология изучает живые организмы и их взаимодействие в природе.', '', '', '', 'default.wav', 'default.svg', 'Биология', '5', 'none'),
    ('Что такое клетка?', 0, 'Клетка - это основная структурная и функциональная единица живых организмов.', '', '', '', 'default.wav', 'default.svg', 'Биология', '5', 'none'),
    ('Какие органы составляют человеческое тело?', 0, 'Человеческое тело состоит из разных систем, включая нервную, кровеносную, дыхательную и др.', '', '', '', 'default.wav', 'default.svg', 'Биология', '5', 'none'),
    ('Что такое экосистема?', 0, 'Экосистема - это сообщество живых организмов и их окружающая среда, где происходит обмен веществ и энергии.', '', '', '', 'default.wav', 'default.svg', 'Биология', '5', 'none'),
    ('Какие виды живых организмов существуют?', 0, 'Существует много видов живых организмов, включая растения, животных, грибы, бактерии и другие.', '', '', '', 'default.wav', 'default.svg', 'Биология', '5', 'none');

INSERT INTO testing_dialogs (question, answer_correct, answer_0, answer_1, answer_2, answer_3, audio_url, image_url, subjects, classes, status) VALUES
    ('Что такое естествознание?', 0, 'Естествознание изучает природу и явления в окружающем мире.', '', '', '', 'default.wav', 'default.svg', 'Естествознание', '5', 'none'),
    ('Какие виды естествознания существуют?', 0, 'Есть разные виды естествознания, включая физику, химию, биологию, астрономию и геологию.', '', '', '', 'default.wav', 'default.svg', 'Естествознание', '5', 'none'),
    ('Что такое планеты солнечной системы?', 0, 'Планеты солнечной системы - это небесные тела, вращающиеся вокруг Солнца, включая Землю, Марс, Венеру и другие.', '', '', '', 'default.wav', 'default.svg', 'Естествознание', '5', 'none'),
    ('Что такое гравитация?', 0, 'Гравитация - это сила, которая притягивает все объекты с массой друг к другу.', '', '', '', 'default.wav', 'default.svg', 'Естествознание', '5', 'none'),
    ('Что такое экология?', 0, 'Экология изучает взаимодействие живых организмов с окружающей средой.', '', '', '', 'default.wav', 'default.svg', 'Естествознание', '5', 'none');



