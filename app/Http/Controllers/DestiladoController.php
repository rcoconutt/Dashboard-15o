<?php

namespace App\Http\Controllers;

use App\Destilado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DestiladoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $destilados = Destilado::where('ID_DESTILADO', '!=', 0)->where('DESTILADO', '!=', 'Whiskey')->select('ID_DESTILADO', 'ID_GRUPO', 'DESTILADO')->groupBy('DESTILADO')->orderBy('ID_DESTILADO')->get();
            return response()->json([
                'CAT_DESTILADO' => [
                    "records" => $destilados
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'destilado' => 'required|string|min:2',
                'nombre' => 'required|string|min:2',
                'status' => 'required|numeric',
                'anejamiento' => 'required',
                'caracteristicas' => 'required|string|min:3',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            $anejamientos[] = $request->get('anejamiento');
            $destilado = $request->get('destilado');
            // Valida si existe el ID_GRUPO
            $checkDestilado = Destilado::where('DESTILADO')->first();
            $grupo_id = null;

            if ($checkDestilado) {
                $grupo_id = $checkDestilado->ID_GRUPO;
            } else {
                $checkDestilado = Destilado::whereRaw('ID_GRUPO = (select max(`ID_GRUPO`) from CAT_DESTILADO)')->first();
                $grupo_id = $checkDestilado->ID_GRUPO;
            }

            foreach ($anejamientos as $anejamiento) {
                Destilado::create([
                    'ID_GRUPO' => $grupo_id,
                    'DESTILADO' => $destilado,
                    'IMAGEN',
                    'ANEJAMIENTO' => $anejamiento,
                    'CARACTERISTICAS' => $request->get('caracteristicas'),
                    'FECHA_ALTA' => Carbon::now(),
                    'FECHA_BAJA' => null,
                    'ACTIVO' => $request->get('status'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'El destilado se creo correctamente',
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
