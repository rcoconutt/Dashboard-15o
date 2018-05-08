<?php
/**
 * Created by PhpStorm.
 * User: rosoriol
 * Date: 27/03/18
 * Time: 02:18 PM
 */

namespace App\Traits;


use App\Notificacion;
use App\User;
use App\Usuario;
use Illuminate\Support\Facades\Mail;

trait NotificacionTrait
{
    public function email(User $user, $notificacion, $message) {
        Mail::to($user->email)->send(new \App\Mail\NotificacionMail($notificacion, $user->name . " " . $user->last_name, $message));
    }

    public function push(Usuario $user, $message, $title) {
        if ($user->token_device) {
            //$key = 'AAAAIHffR28:APA91bGvkTNUFh1iCgUUzb1MKLT-rpgG3xg3lSCZPRhAO6EzDQL02kxoI6Gujh1q1gIYRhIKGIUWrTpc-pwTAU9az4zIWUeabUA0ZfRaQ8cCrHzeXkNfDgILnMGLEfMh5O9ZJ0dppJIp';
            $key = 'AAAAxs4WPC0:APA91bHDeOOphTZjok7uwyN2vwIyJdsQoS6qObIXRYG0qu8mb2l6iyassUca4yCF18rtWv5QLckmZlwmG_MWeF3uoI65AX5kYvrxqhB8YqK7eWGms7qz18hdbYnsMdELxRJi4N-QpHjO';
            $msg = [
                //'body' => (strlen($notification) > 38) ? substr($notification, 0, 35) . '...' : $notification,
                'body' => $message,
                'title' => $title
            ];

            $fields = [
                'to' => $user->token_device,
                'notification' => $msg
            ];

            $headers = [
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $response = curl_exec($ch);
            curl_close($ch);
        }
    }
}