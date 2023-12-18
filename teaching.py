import openpyxl
import json
import mysql.connector

workbook = openpyxl.load_workbook('exel/Химия обучение.xlsx')
#Биология обучение
#Ествествознание обучение
#Химия обучение
#Физика обучение

sheet = workbook.active

columns = [cell.value for cell in sheet[1]]

db_config = {
    "host": "localhost",  
    "user": "root",  
    "password": "",  
    "database": "sferaschool"  
}

connection = mysql.connector.connect(**db_config)
cursor = connection.cursor()

for row in sheet.iter_rows(min_row=2, values_only=True):
    record = {}
    valid_data = False  

    for i, value in enumerate(row):
        if columns[i] in ('question', 'answer_0', 'subjects', 'classes'):
            if value is not None:
                valid_data = True 
                if isinstance(value, str):
                    value = value.replace('\n', '')  
                record[columns[i]] = value

    if valid_data:
        record['image_url'] = 'default.svg'
        record['audio_url'] = 'default.wav'
        record['answer_correct'] = '0'

        insert_query = """
        INSERT INTO teaching_dialogs (question, answer_0, subjects, classes, image_url, audio_url, answer_correct)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
        """
        cursor.execute(insert_query, (
            record.get('question', ''),
            record.get('answer_0', ''),
            record.get('subjects', ''),
            record.get('classes', ''),
            record.get('image_url', 'default.svg'),
            record.get('audio_url', 'default.wav'),
            record.get('answer_correct', '0')
        ))
        connection.commit()

cursor.close()
connection.close()
