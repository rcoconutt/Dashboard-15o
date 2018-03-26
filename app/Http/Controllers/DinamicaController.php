<?php

namespace App\Http\Controllers;

use App\Dinamica;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DinamicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dinamicas.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'dinamicas' => Dinamica::all()
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dinamicas.create');
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
                'name' => 'required|min:6',
                'premio' => 'required|min:6',
                'venue' => 'required|numeric',
                'zona' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 401);
            }

            $cantidad = -1;
            $descripción = $request->get('reglas');
            $marca = -1;
            if ($request->get('kind') == 1) {
                $cantidad = $request->get('cantidad');
                $descripción = $request->get('descripcion');
                $marca = $request->get('marca');
            }

            if ($descripción == null) {
                return response()->json(['success' => false, 'message' => "Ingresa las reglas de la dinámica"], 401);
            }

            $dinamica = Dinamica::create([
                'ID_ZONA' => 0,
                'ID_BRAND' => 0,
                'OBJETIVO' => $cantidad,
                'DINAMICA' => $request->get('name'),
                'DESCRIPCION' => $descripción,
                'PREMIO' => $request->get('premio'),
                'FECHA_INICIO' => Carbon::parse($request->get('start')),
                'FECHA_FIN' => Carbon::parse($request->get('end')),
                'ACTIVO' => 0,
                'municipio_id' => $request->get('zona'),
                'marca_id' => $marca
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dínamica creada correctamente',
                'dinamica' => $dinamica
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dinamica  $dinamica
     * @return \Illuminate\Http\Response
     */
    public function show(Dinamica $dinamica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dinamica  $dinamica
     * @return \Illuminate\Http\Response
     */
    public function edit(Dinamica $dinamica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dinamica  $dinamica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dinamica $dinamica)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dinamica  $dinamica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dinamica $dinamica)
    {
        //
    }
}