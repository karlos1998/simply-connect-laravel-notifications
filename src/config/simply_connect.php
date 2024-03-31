<?php

return [
    'api_key' => env('SIMPLY_CONNECT_API_KEY', 'your-default-api-key'),
    'default_device_id' => env('SIMPLY_CONNECT_DEFAULT_DEVICE_ID'),

    /**
     * EN: The name of the column in the model, e.g. User, which the notification service is to use
     * Remember that it is not required so and you can use the ->phoneNumber(...) function to create a notification by passing a string with the phone number
     *
     * PL: Nazwa kolumny w modelu np User, z której ma korzystac serwis powiadomień
     * Pamiętaj, że nie jest ona wymagana i możesz użyć funkcji ->phoneNumber(...) tworząc notyfikację przekazując string z numerem telefonu
     */
    'model_phone_number_column' => 'phone_number'
];
