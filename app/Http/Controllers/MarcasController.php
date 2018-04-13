<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Dinamica;
use App\Marca;
use Illuminate\Http\Request;

class MarcasController extends Controller
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
                'marcas' => Marca::all()
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
                'marcas' => Marca::select('ID_MARCA', 'MARCA')->where('MARCA', 'LIKE', '%' . $q . '%')->get()
                //'marcas' => Marca::all()
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
        }
    }

    public function marcas($brand_id = null)
    {
        if ($brand_id != null && is_numeric($brand_id) && $brand_id > 0) {
            $dinamicas = Dinamica::select('marca_id', 'ID_BRAND')->where('ID_BRAND', $brand_id)->get();
            $marcas = Marca::select('ID_MARCA', 'MARCA')->whereIn('ID_MARCA', $dinamicas->pluck('ID_MARCA'))->get();
        } else {
            $marcas = Marca::select('ID_MARCA', 'MARCA')->get();
        }

        try {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'marcas' => $marcas
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(Marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marca $marca)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marca $marca)
    {
        //
    }
}
