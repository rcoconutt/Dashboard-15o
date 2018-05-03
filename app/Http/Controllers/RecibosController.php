<?php

namespace App\Http\Controllers;

use App\Recibo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'ID_CENTRO' => 'required|numeric',
                'ID_USUARIO' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 401);
            }

            //$recibo = $request->get('RECIBO');
            $file_data = $request->input('RECIBO');
            $file_name = 'image_'.time().'.png';
            list($type, $file_data) = explode(';', $file_data);
            list(, $file_data) = explode(',', $file_data);
            if ($file_data != "") {
                Storage::disk('public')->put($file_name,base64_decode($file_data));
            }

            $recibo = Recibo::create([
                'ID_CENTRO' => $request->get('ID_CENTRO'),
                'ID_USUARIO' => $request->get('ID_USUARIO'),
                'RECIBO' => null,
                'NUMERO' => null,
                'FECHA' => Carbon::now(),
                'status' => 0,
                //'url' =>
            ]);

            return response()->json(['success' => false, 'message' => 'El recibo se creo correctamente']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage() . $ex->getFile() . $ex->getLine()], 500);
        }
    }
}
