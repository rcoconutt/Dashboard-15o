<?php

namespace App\Http\Controllers;

use App\Desgloce;
use App\Dinamica;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PuntosController extends Controller
{
    public function topByMarca($dinamica_id) {
        try {
            $dinamica = Dinamica::where('ID_DINAMICA', $dinamica_id)->firstOrFail();
            $desgloces = Desgloce::with('recibo', 'recibo.usuario')->where('ID_MARCA', $dinamica->marca_id)->get();
            $users = collect();
            foreach ($desgloces as $desgloce) {
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

            $users = $users->sortByDesc(function ($product, $key) {
                return $product->total;
            });

            $users = $users->values()->all();

            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => $users
            ], 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['success' => false, 'message' => "No se encontró la dinámica"], 500);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
        }

    }
}
