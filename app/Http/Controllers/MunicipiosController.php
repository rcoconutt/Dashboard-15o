<?php

namespace App\Http\Controllers;

use App\Municipio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MunicipiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'municipios' => Municipio::all()
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    public function search($q)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'municipios' => Municipio::where('MUNICIPIO', 'LIKE', '%' . $q . '%')->get()
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
        //
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
                'estado' => 'required|numeric',
                'nombre' => 'required|string|min:2',
                'abreviatura' => 'required|string|min:2',
                'status' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'El municipio se creo correctamente',
                'municipio' => Municipio::create([
                    "ID_ESTADO" => $request->get('estado'),
                    "ID_ZONA" => 0,
                    "ABREVIATURA" => $request->get('abreviatura'),
                    'MUNICIPIO' => $request->get('nombre'),
                    'FECHA_ALTA' => Carbon::now(),
                    'FECHA_BAJA' => null,
                    'ACTIVO' => $request->get('status'),
                ])
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function show(Municipio $municipio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipio $municipio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipio $municipio)
    {
        try {
            $validator = Validator::make($request->all(), [
                'estado' => 'required|numeric',
                'nombre' => 'required|string|min:2',
                'abreviatura' => 'required|string|min:2',
                'status' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            $municipio->update([
                "ID_ESTADO" => $request->get('estado'),
                "ABREVIATURA" => $request->get('abreviatura'),
                'MUNICIPIO' => $request->get('nombre'),
                'ACTIVO' => $request->get('status'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'El municipio se actualizó correctamente!',
                'municipio' => $municipio
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipio $municipio)
    {
        try {
            $municipio->delete();

            return response()->json([
                'success' => true,
                'message' => 'El municipio se eliminó correctamente',
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }
}
