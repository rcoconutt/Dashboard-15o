<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Destilado;
use App\Dinamica;
use App\Marca;
use App\Traits\ClearString;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MarcasController extends Controller
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
                'marcas' => Marca::select('ID_MARCA', 'ID_PAIS', 'ID_DESTILADO', 'MARCA', 'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO')->with('destilado')->get()
            ], 200);
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
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
                Marca::whereIn('ID_MARCA', $request->get('marca_id'))->update(['ACTIVO' => $action]);
                if ($action == 1) {
                    return redirect()->back()->with('message', 'Marcas aprobadas correctamente!');
                } else {
                    return redirect()->back()->with('message', 'Marcas rechazadas correctamente!');
                }
            } else {
                // Eliminar
                Marca::whereIn('ID_MARCA', $request->get('marca_id'))->delete();
                return redirect()->back()->with('message', 'Marcas eliminadas correctamente!');
            }
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
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

    public function create()
    {
        $destilados = Destilado::select('ID_DESTILADO', 'DESTILADO', 'ANEJAMIENTO')->get();
        return view('marcas.create', compact('destilados'));
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
                'marca' => 'required|string|min:2',
                'destilado' => 'required|numeric',
                'estado' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            $marca = Marca::create([
                'ID_PAIS' => 0,
                'FECHA_ALTA' => Carbon::now(),
                'FECHA_BAJA' => null,
                'ID_DESTILADO' => $request->get('destilado'),
                'MARCA' => $this->clearString($request->get('marca')),
                'ACTIVO' => $request->get('estado'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'La marca se creó correctamente!'
            ], 201);
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(Marca $marca)
    {
        return view('marcas.index');
    }

    public function edit($marca)
    {
        try {
            $destilados = Destilado::select('ID_DESTILADO', 'DESTILADO', 'ANEJAMIENTO')->get();
            $marca = Marca::select('ID_MARCA', 'ID_PAIS', 'ID_DESTILADO', 'MARCA', 'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO')->where('ID_MARCA', $marca)->firstOrFail();
            return view('marcas.update', compact('marca', 'destilados'));
        } catch (ModelNotFoundException $ex) {
            return redirect()->back()->withErrors(['error' => "Destilado no encontrada"]);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $marca)
    {
        try {
            $validator = Validator::make($request->all(), [
                'marca' => 'required|string|min:2',
                'destilado' => 'required|numeric',
                'estado' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 400);
            }

            $marca = Marca::where('ID_MARCA', $marca)->first();
            $marca->update([
                'ID_DESTILADO' => $request->get('destilado'),
                'MARCA' => $this->clearString($request->get('marca')),
                'ACTIVO' => $request->get('estado'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'La marca se actualizó correctamente!'
            ], 201);
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
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
