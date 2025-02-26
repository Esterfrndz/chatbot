<?php

//Key 
$geminiApiKey = 'AIzaSyB4RtIJakmNOrxAjHb5zEB2CB8-Y5qdJII';

// Obtiene el input del POST
$input = isset($_POST['input']) ? $_POST['input'] : '';

if (!empty($input)) { // Verificar si se recibió un input

    function generateGeminiContent($apiKey, $modelName, $input)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/" . $modelName . ":generateContent?key=" . $apiKey;

        // Registro de errores
        error_log("URL de la API: " . $url);

        //Array de la solicitud

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $input]
                    ]
                ]
            ]
        ];

        //Array de options 
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_VERBOSE => false,
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        //Inicia una sesion cURL
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //Verifica si fue exitosa
        if ($httpCode == 200 && $response !== false) {
            return ['success' => true, 'response' => $response];
        } else {
            return ['success' => false, 'error' => 'Error al generar contenido. Código HTTP: ' . $httpCode . ', Respuesta: ' . $response];
        }
    }

    //Variable del modelo utilizado
    $selectedModel = 'models/gemini-1.5-pro-latest';

    // Registro de modelo seleccionado 
    error_log("Modelo seleccionado: " . $selectedModel);

    //Llamada a la función
    $contentResult = generateGeminiContent($geminiApiKey, $selectedModel, $input);

    //Si es exitosa decodifica JSON y muestra el texto generado por Gemini
    if ($contentResult['success']) {
        $contentDecodedResponse = json_decode($contentResult['response'], true);

        error_log(print_r($contentDecodedResponse, true)); // Depuración: Mostrar respuesta de Gemini

        if (isset($contentDecodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $geminiReply = $contentDecodedResponse['candidates'][0]['content']['parts'][0]['text'];
            echo json_encode(['response' => $geminiReply]);
        } else { //muestra el error
            echo json_encode(['error' => 'Gemini no generó texto.']);
        }
    } else { //muestra el error
        echo json_encode(['error' => $contentResult['error']]);
    }
} else { //Si no se ha recibido ningún input

    echo json_encode(['error' => 'No se recibió ningún input.']);
}
?>