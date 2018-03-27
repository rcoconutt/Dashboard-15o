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
use Illuminate\Support\Facades\Mail;

trait NotificacionTrait
{
    public function email(User $user, $notificacion, $message) {
        Mail::to($user->email)->send(new \App\Mail\NotificacionMail($notificacion, $user->name . " " . $user->last_name, $message));
    }
}