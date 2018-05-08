<?php

namespace App\Http\Controllers;

use App\Estado;
use App\Municipio;
use App\Traits\ClearString;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MunicipiosController extends Controller
{
    use ClearString;

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
                'municipios' => Municipio::with('estado')->get()
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
        $estados = Estado::all();
        return view('zonas.create', compact('estados'));
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
                'message' => 'La zona se creo correctamente',
                'municipio' => Municipio::create([
                    "ID_ESTADO" => $request->get('estado'),
                    "ID_ZONA" => 0,
                    "ABREVIATURA" => $this->clearString($request->get('abreviatura')),
                    'MUNICIPIO' => $this->clearString($request->get('nombre')),
                    'FECHA_ALTA' => Carbon::now(),
                    'FECHA_BAJA' => null,
                    'ACTIVO' => $request->get('status'),
                ])
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    public function show()
    {
        return view('zonas.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function edit($municipio)
    {
        try {
            $municipio = Municipio::where('ID_MUNICIPIO', $municipio)->firstOrFail();
            $estados = Estado::all();

            return view('zonas.update', compact('municipio', 'estados'));
        } catch (ModelNotFoundException $ex) {
            return redirect()->back()->withErrors(['error' => "Zona no encontrada"]);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
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
                Municipio::whereIn('ID_MUNICIPIO', $request->get('municipio_id'))->update(['ACTIVO' => $action]);
                if ($action == 1) {
                    return redirect()->back()->with('message', 'Zonas habilitadas correctamente!');
                } else {
                    return redirect()->back()->with('message', 'Zonas deshabilitadas correctamente! ');
                }
            } else {
                // Eliminar
                Municipio::whereIn('ID_MUNICIPIO', $request->get('municipio_id'))->delete();
                return redirect()->back()->with('message', 'Zonas eliminadas correctamente!');
            }
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
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
                "ABREVIATURA" => $this->clearString($request->get('abreviatura')),
                'MUNICIPIO' => $this->clearString($request->get('nombre')),
                'ACTIVO' => $request->get('status'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'La zona se actualizó correctamente!',
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
