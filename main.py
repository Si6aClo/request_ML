# Импорт библиотек
import requests
import json

# Ссылка для получения токена авторизации
url = "https://api.aicloud.sbercloud.ru/public/v2/auth"
# Ссылка для получения ответа от нейросети
url_predict = "https://api.aicloud.sbercloud.ru/public/v2/inference/v1/predict/kfserving-1628088658/kfserving-1628088658/"

# Данные из workspace
api_key = 'cf0dede4-c3a2-421e-a848-f96ab2605d41'
workspace_id = '50712d76-ef35-4ba8-948b-aaca27062a90'

# Почта и пароль от sber-cloud
email = "sevaaa00@gmail.com"
password = "Aquatech102104$$"

# Подготавливаем данные тела запроса
payload = json.dumps({
  "email": email,
  "password": password
})
# header запроса
headers = {
  'Content-Type': 'application/json',
}

# Посылаем Post запрос
response = requests.request("POST", url, headers=headers, data=payload)

# Обработка ответа и получение токена
jsonFile = response.json()
token = jsonFile['token']['access_token']

# Подготавливаем тело запроса для нейронки
payload_new = json.dumps({
    "instances": [
        {
            "records": {
                'PassengerId': [1],
                'Pclass': [3],
                'Name': ["Braund, Mr. Owen Harris"],
                'Sex': ["male"],
                'Age': [22],
                'SibSp': [1],
                'Parch': ["0"],
                'Ticket': ["A/5 21171"],
                'Fare': ["7.25"],
                'Cabin': [""],
                'Embarked': ["S"],
            }

        }
    ]
})

# Подготавливаем header для нейронки
headers_new = {
  'Content-Type': 'application/json',
  'Authorization': token,
  'x-api-key': api_key,
  'x-workspace-id': workspace_id
}

# Отправляем ещё 1 запрос, только уже нейронке с новыми данными
response_predict = requests.request("POST", url_predict, headers=headers_new, data=payload_new)

# Обрабатываем и выводим ответ
predict = response_predict.json()
print(predict['predictions'][0][0])
