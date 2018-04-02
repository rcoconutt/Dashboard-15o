<?php

namespace App\Http\Controllers;

use App\Recibo;
use Illuminate\Http\Request;

class RecibosController extends Controller
{
    public function api($brand_id = null)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'tickets' => Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')->with('usuario', 'centro')->get()
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
        }
    }

    public function show($recibo)
    {
        try {
            $recibo = Recibo::where('ID_RECIBO', $recibo)->with('usuario', 'centro', 'desgloce')->firstOrFail();

            $recibo->total = 0;
            foreach ($recibo->desgloce as $detalle) {
                $recibo->total += $detalle->CANTIDAD;
            }

            return view('admin.recibo', compact('recibo'));
        } catch (\Exception $ex) {
            return redirect('/admin')->withErrors(["error" => 'Recibo no encontrado']);
        }
    }

    public function update($recibo, Request $request)
    {
        try {
            $recibo = Recibo::where('ID_RECIBO', $recibo)->update(['status' => $request->get('action')]);

            return redirect()->back()->with('message', 'El recibo se actualizó correctamente');
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(["error" => $ex->getMessage()]);
        }
    }
}
