CREATE TABLE statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    answers_correct INT,
    dialogs INT,
    subjects TEXT,
    classes TEXT,
    classes_letter TEXT,
    precent TEXT,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);