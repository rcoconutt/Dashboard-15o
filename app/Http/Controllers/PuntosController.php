<?php

namespace App\Http\Controllers;

use App\Desgloce;
use App\Dinamica;
use Illuminate\Http\Request;

class PuntosController extends Controller
{
    public function topByMarca($dinamica_id) {
        try {
            $dinamica = Dinamica::where('ID_DINAMICA', $dinamica_id)->first();
            $desgloces = Desgloce::with('recibo', 'recibo.usuario')->where('ID_MARCA', $dinamica->marca_id)->get();
            $users = collect();
            foreach ($desgloces as $desgloce) {
                //array_push($users_ids, $desgloce->recibo->usuario->ID_USUARIO);
                $users->push($desgloce->recibo->usuario);
            }

            $users = $users->unique();

            foreach ($users as $user) {
                $total = 0;

                foreach ($desgloces as $desgloce) {
                    if ($desgloce->recibo->usuario->ID_USUARIO == $user->ID_USUARIO) {
                        $total += $desgloce->CANTIDAD;
                    }
                }
                $user->total = $total;
            }

            /*
            $users = $users->sortByDesc(function ($user, $key) {
                print_r($key);
                return $user->total;
            });
            */

            return response()->json([
                'success' => true,
                'message' => 'Dínamica creada correctamente',
                //'data' => $desgloces
                'data' => $users
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage() . $ex->getFile() . $ex->getLine()], 500);
        }

    }
}
