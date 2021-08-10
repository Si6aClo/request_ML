<?php

// Инициализация curl запроса
$curl = curl_init();

// Ссылка для получения токена
$url = 'https://api.aicloud.sbercloud.ru/public/v2/auth';

// Установка настроек запроса
curl_setopt_array($curl, array(
    CURLOPT_URL => $url, // Указываем ссылку
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0, // Максимальное время ожидания ответа, при 0 неограниченное время
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST', // POST запрос
    CURLOPT_POSTFIELDS => '{
    "email": "YOUR-EMAIL",
    "password": "YOUR-PASSWORD"
    }', // Указание email и password
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ), // Указываем Content-Type
));

// Получение запроса
$response = curl_exec($curl);

// Закрытие сессии
curl_close($curl);

// Ответ возвращается строкой, поэтому выцепляем токен с помощью регулярных выражений
preg_match_all('/"access_token":"[^"]*/', $response, $data);
$token_str = $data[0];
$token = $token_str[0];
$token = preg_replace('/"access_token":"/', '', $token);

// Инициализируем новую сессию
$curl_predict = curl_init();

// Ссылка для нейронки
$url_predict = 'YOUR-URL-FOR-PREDICT';

// Из параметров разработчика
$api_key = 'YOUR-API-KEY';
$workspace_id = 'YOUR-WORKSPACE-ID';

curl_setopt_array($curl_predict, array(
    CURLOPT_URL => $url_predict,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
        "instances": [
                {
                    "records": {
                        "PassengerId": [1],
                        "Pclass": [3],
                        "Name": ["Braund, Mr. Owen Harris"],
                        "Sex": ["male"],
                        "Age": [22],
                        "SibSp": [1],
                        "Parch": ["0"],
                        "Ticket": ["A/5 21171"],
                        "Fare": ["7.25"],
                        "Cabin": [""],
                        "Embarked": ["S"]
                    }
                }
            ]
        }', // Добавляем данные, про них написано в страничке про python
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'x-api-key: ' . $api_key,
        'Authorization: ' . $token,
        'x-workspace-id: ' . $workspace_id
    ), // Конкатенацией делаем поля в header запроса
));

// Получаем predict
$predict = curl_exec($curl_predict);

//Закрываем сессию
curl_close($curl_predict);

// Опять же с помощью регулярных выражений получаем ответ
preg_match_all('/\[\[[^"]*\]\]/', $predict, $data_predict);
$predict_str = $data_predict[0];
$predict_value = $predict_str[0];
$predict_value = preg_replace('/\[/', '', $predict_value);
$predict_value = preg_replace('/\]/', '', $predict_value);
// Вывод ответа
echo $predict_value;
?>
