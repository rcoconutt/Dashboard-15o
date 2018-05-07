<?php

namespace App\Http\Controllers;

use App\Destilado;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function api()
    {
        try {
            $destilados = Destilado::select('ID_DESTILADO', 'ID_GRUPO', 'DESTILADO', 'ANEJAMIENTO', 'CARACTERISTICAS', 'ACTIVO')->orderBy('ID_DESTILADO')->get();
            return response()->json([
                'success' => true,
                'message' => 'success',
                'destilados' => $destilados
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage()], 500);
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
                Destilado::whereIn('ID_DESTILADO', $request->get('destilado_id'))->update(['ACTIVO' => $action]);
                if ($action == 1) {
                    return redirect()->back()->with('message', 'Destilados habilitados correctamente!');
                } else {
                    return redirect()->back()->with('message', 'Destilados deshabilitados correctamente! ');
                }
            } else {
                // Eliminar
                Destilado::whereIn('ID_DESTILADO', $request->get('destilado_id'))->delete();
                return redirect()->back()->with('message', 'Destilados eliminados correctamente!');
            }
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
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
                'anejamiento' => 'required',
                'caracteristicas' => 'required|string|min:3',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }

            $anejamientos[] = $request->get('anejamiento');
            $destilado = htmlentities($request->get('destilado'));
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
                    'ANEJAMIENTO' => htmlentities($anejamiento),
                    'CARACTERISTICAS' => htmlentities($request->get('caracteristicas')),
                    'FECHA_ALTA' => Carbon::now(),
                    'FECHA_BAJA' => null,
                    'ACTIVO' => 0,
                ]);
            }

            return redirect('/destilados')->with('message', "El destilado se creo correctamente");
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(["error" => $ex->getMessage()]);
        }
    }

    public function edit($destilado) {
        try {
            $destilado = Destilado::where('ID_DESTILADO', $destilado)->firstOrFail();
            return view('destilados.update', compact('destilado', 'imagen'));
        } catch (ModelNotFoundException $ex) {
            return redirect()->back()->withErrors(['error' => "Destilado no encontrada"]);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
    }

    public function create() {
        return view('destilados.create');
    }

    public function show()
    {
        return view('destilados.index');
    }

    public function update(Request $request, $destilado)
    {
        try {
            $validator = Validator::make($request->all(), [
                'destilado' => 'required|string|min:2',
                'anejamiento' => 'required|string|min:2',
                'caracteristicas' => 'required|string|min:2',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                //return response()->json(['success' => false, 'message' => $errors->first()], 400);
                return redirect()->back()->withErrors($errors);
            }

            $destilado = Destilado::where('ID_DESTILADO', $destilado)->first();

            $imagen = $request->file('logo');
            if ($imagen != null) {
                $rules = ['file' => 'required|image|mimes:jpeg,png,jpg|max:2048'];
                $validator = Validator::make(['file' => $imagen], $rules);
                if ($validator->passes()) {
                    /*
                    $today = Carbon::now();
                    $path = $photo->storeAs("/public/" . $today->year . "/" . $today->month . "/" . $today->day, uniqid() . "." . $photo->getClientOriginalExtension());
                    ProductPhoto::create([
                        'product_id' => $product->id,
                        'photo_url' => Storage::url($path),
                        'thumbnail_url' => Storage::url($path),
                        'size' => $photo->getSize(),
                        'name' => $photo->getClientOriginalName()
                    ]);
                    */
                }
            }

            $destilado->update([
                'DESTILADO' => htmlentities($request->get('destilado')),
                'ANEJAMIENTO' => htmlentities($request->get('anejamiento')),
                'CARACTERISTICAS' => htmlentities($request->get('caracteristicas')),
            ]);

            return redirect('/destilados')->with('message', "El destilado se actualizó correctamente");
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(["error" => $ex->getMessage()]);
        }
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
