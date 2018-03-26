<?php

namespace App\Http\Controllers;

use App\Municipio;
use Illuminate\Http\Request;

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipio $municipio)
    {
        //
    }
}
