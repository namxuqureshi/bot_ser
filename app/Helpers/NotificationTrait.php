<?php
namespace App\Helpers;
trait NotificationTrait
{

    function send_notification($tokens, $message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
        );
        $headers = array(
            'Authorization:key = AAAArlRWJYM:APA91bFl6wN-xnE2SYP2LOU5AHBFy6GSwbMzVqAcfwl6pkBIcEZqbpmD1jeRo8m0XJtIKTqDN8SlCvMv8GFKHHxTX8qpL2G6uF-GhPse-0FScDejixDfEdQTwf6tEytYq53eO26tgwD9',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));//json_encode($fields)
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}