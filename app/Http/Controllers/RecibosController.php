<?php

namespace App\Http\Controllers;

use App\Desgloce;
use App\Recibo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                'tickets' => Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')->orderBy('FECHA', 'desc')->with('usuario', 'centro')->get()
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

    public function action(Request $request) {
        try {
            // Eliminar
            Desgloce::whereIn('ID_RECIBO', $request->get('recibo_id'))->delete();
            Recibo::whereIn('ID_RECIBO', $request->get('recibo_id'))->delete();

            return redirect()->back()->with('message', 'Tickets eliminados correctamente!');
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
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
            $file_name = '/' . Carbon::now()->year . '/' . Carbon::now()->month . '/recibo_' . time() . '.png';
            list($type, $file_data) = explode(';', $file_data);
            list(, $file_data) = explode(',', $file_data);
            $disk = Storage::disk('gcs');

            if ($file_data != "") {
                $disk->put($file_name, base64_decode($file_data));
                $disk->setVisibility($file_name, 'public');
            }

            $recibo = Recibo::create([
                'ID_CENTRO' => $request->get('ID_CENTRO'),
                'ID_USUARIO' => $request->get('ID_USUARIO'),
                'RECIBO' => null,
                'NUMERO' => null,
                'FECHA' => Carbon::now(),
                'status' => 0,
                'url' => $disk->url($file_name)
            ]);

            return $recibo->ID_RECIBO;
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return $ex->getMessage();
        }
    }
}
