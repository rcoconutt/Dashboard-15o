<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Municipio;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                $venues = Venue::with('municipio')->get();
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

    public function action(Request $request) {
        try {
            $action = $request->get('actions');
            if ($action == 1 || $action == 2) {
                if ($action == 2) {
                    $action = 0;
                }
                // Aprobar o rechazar
                Venue::whereIn('ID_CENTRO', $request->get('centro_id'))->update(['ACTIVO' => $action]);
                if ($action == 1) {
                    return redirect()->back()->with('message', 'Centros habilitados correctamente!');
                } else {
                    return redirect()->back()->with('message', 'Centros deshabilitados correctamente! ');
                }
            } else {
                // Eliminar
                Venue::whereIn('ID_CENTRO', $request->get('centro_id'))->delete();
                return redirect()->back()->with('message', 'Centros eliminadas correctamente!');
            }
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
    }

    public function create() {
        $zonas = Municipio::all();
        return view('venues.create', compact('zonas'));
    }

    public function show()
    {
        return view('venues.index');
    }

    public function edit($venue)
    {
        try {
            $venue = Venue::where('ID_CENTRO', $venue)->firstOrFail();
            $zonas = Municipio::all();

            return view('venues.update', compact('venue', 'zonas'));
        } catch (ModelNotFoundException $ex) {
            return redirect()->back()->withErrors(['error' => "Zona no encontrada"]);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
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
