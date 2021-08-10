<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.aicloud.sbercloud.ru/public/v2/auth',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
        "email": "sevaaa00@gmail.com",
        "password": "Aquatech102104$$"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    preg_match_all('/"access_token":"[^"]*/', $response, $data);
    $token_str = $data[0];
    $token = $token_str[0];
    $token = preg_replace('/"access_token":"/', '', $token);


    $curl_predict = curl_init();

    $url_predict = 'https://api.aicloud.sbercloud.ru/public/v2/inference/v1/predict/kfserving-1628088658/kfserving-1628088658/';

    $api_key = 'cf0dede4-c3a2-421e-a848-f96ab2605d41';
    $workspace_id = '50712d76-ef35-4ba8-948b-aaca27062a90';

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
}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-key: ' . $api_key,
            'Authorization: ' . $token,
            'x-workspace-id: ' . $workspace_id
        ),
    ));

    $predict = curl_exec($curl_predict);

    curl_close($curl_predict);

    preg_match_all('/\[\[[^"]*\]\]/', $predict, $data_predict);
    $predict_str = $data_predict[0];
    $predict_value = $predict_str[0];
    $predict_value = preg_replace('/\[/', '', $predict_value);
    $predict_value = preg_replace('/\]/', '', $predict_value);
    echo $predict_value;
    ?>
</body>

</html>