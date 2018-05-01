<?php

namespace App\Http\Controllers;

use App\Desgloce;
use App\Dinamica;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PuntosController extends Controller
{
    public function topByMarca($dinamica_id) {
        try {
            $dinamica = Dinamica::where('ID_DINAMICA', $dinamica_id)->firstOrFail();
            if ($dinamica->marca_id > 0) {
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
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://plisasegura.me/test/api.php/CTL_PUNTOS?include=CAT_PUNTO,CAT_USUARIO&transform=true&columns=CAT_PUNTO.VALOR,CAT_USUARIO.NOMBRE,CAT_USUARIO.ID_CENTRO7");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);

                return response($output);
            }
        } catch (ModelNotFoundException $ex) {
            return response()->json(['success' => false, 'message' => "No se encontró la dinámica"], 400);
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());

            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
        }

    }
}
