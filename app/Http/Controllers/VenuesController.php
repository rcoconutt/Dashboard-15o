<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($municipio_id = null)
    {
        try {
            if ($municipio_id != null) {
                $venues = Venue::where('ID_MUNICIPIO', $municipio_id)->get();
            } else {
                $venues = Venue::all();
            }

            return response()->json([
                'success' => true,
                'message' => 'success',
                'venues' => $venues
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    public function search($venue)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'venues' => Venue::where('CENTRO', 'LIKE', '%' . $venue . '%')->get()
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
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
                'municipio' => 'required|numeric',
                'nombre' => 'required|string|min:2',
                'status' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'El centro de consumo se creo correctamente',
                'venue' => Centro::create([
                    'ID_MUNICIPIO' => $request->get('municipio'),
                    'CENTRO' => $request->get('nombre'),
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
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue, Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venue $venue)
    {
        try {
            $validator = Validator::make($request->all(), [
                'municipio' => 'required|numeric',
                'nombre' => 'required|string|min:2',
                'status' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            $venue->update([
                'ID_MUNICIPIO' => $request->get('municipio'),
                'CENTRO' => $request->get('nombre'),
                'ACTIVO' => $request->get('status'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'El centro de consumo se actualizó correctamente!',
                'venue' => $venue
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        try {
            $venue->delete();

            return response()->json([
                'success' => true,
                'message' => 'El centro de consumo se eliminó correctamente',
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }
}
