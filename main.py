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

# 
payload = json.dumps({
  "email": email,
  "password": password
})
headers = {
  'Content-Type': 'application/json',
}

response = requests.request("POST", url, headers=headers, data=payload)

jsonFile = response.json()
token = jsonFile['token']['access_token']

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

headers_new = {
  'Content-Type': 'application/json',
  'Authorization': token,
  'x-api-key': api_key,
  'x-workspace-id': workspace_id
}

response_predict = requests.request("POST", url_predict, headers=headers_new, data=payload_new)

predict = response_predict.json()
print(predict['predictions'][0][0])
