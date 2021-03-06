<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Dinamica;
use App\DinamicaHasCenter;
use App\DinamicaHasZone;
use App\Notificacion;
use App\Traits\ClearString;
use App\Traits\NotificacionTrait;
use App\User;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DinamicaController extends Controller
{
    use NotificacionTrait, ClearString;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (Auth::user()->rol) {
            case 0:
                return view('dinamicas.index_admin');
            case 1:
                return view('dinamicas.index_gerente');
            case 2:
                return view('dinamicas.index_supervisor');
            default:
                return view('dinamicas.index_embajador');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api($brand_id = null)
    {
        try {
            if ($brand_id == null) {
                $dinamicas = Dinamica::with('centros', 'zonas')->orderByDesc('ID_DINAMICA')->get();
            } else {
                $dinamicas = Dinamica::with('centros', 'zonas')->where('ID_BRAND', $brand_id)->orderByDesc('ID_DINAMICA')->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'success',
                'dinamicas' => $dinamicas
            ], 200);
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    /*
     * Devuelve las dinámicas por zona o centro de consumo
     */
    public function indexByZona($zona_id, $centro_id)
    {
        try {
            $zonas = DinamicaHasZone::where('zona_id', $zona_id)->get();
            $centros = DinamicaHasCenter::where('centro_id', $centro_id)->get();

            $dinamicas = Dinamica::with('centros', 'zonas')
                ->whereIn('ID_DINAMICA', $zonas->pluck('dinamica_id'))
                ->orWhereIn('ID_DINAMICA', $centros->pluck('dinamica_id'))
                ->orWhere('municipio_id', $zona_id)
                ->orderByDesc('ID_DINAMICA')->get();

            return response()->json([
                'success' => true,
                'message' => 'success',
                'dinamicas' => $dinamicas
            ], 200);
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
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
                    'start' => 'required|date',
                    'end' => 'required|date',
                ],
                [
                    'date'    => 'Selecciona una fecha valida',
                ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 401);
            }

            $zonas = $request->get('zona');
            $venues = $request->get('venue');
            if (count($zonas) < 1) {
                return response()->json(['success' => false, 'message' => "Ingresa al menos una zona"], 401);
            }

            $brand_id = $request->get('brand_id');
            $user_id = $request->get('user_id');
            $autor = User::whereId($user_id)->first();
            $cantidad = -1;
            $name = $this->clearString($request->get('name'));
            $descripción = $request->get('reglas');
            $marca = ($request->get('marca') != null) ? $request->get('marca') : 0;

            if ($request->get('kind') == 1) {
                $cantidad = $request->get('cantidad');
                $descripción = $request->get('descripcion');
            }

            if ($descripción == null) {
                if ($request->get('kind') == 1) {
                    return response()->json(['success' => false, 'message' => "Ingresa la descripción de la dinámica"], 401);
                } else {
                    return response()->json(['success' => false, 'message' => "Ingresa las reglas de la dinámica"], 401);
                }
            }

            $premio = $this->clearString($request->get('premio'));

            $dinamica = Dinamica::create([
                'ID_ZONA' => 0,
                'ID_BRAND' => $brand_id,
                'OBJETIVO' => $cantidad,
                'DINAMICA' => $name,
                'DESCRIPCION' => $this->clearString($descripción),
                'PREMIO' => $premio,
                'FECHA_INICIO' => Carbon::parse($request->get('start')),
                'FECHA_FIN' => Carbon::parse($request->get('end')),
                'ACTIVO' => 0,
                'municipio_id' => 0,
                'marca_id' => $marca,
                'user_id' => $user_id,
                'tipo_consumo' => $request->get('tipo_consumo')
            ]);

            foreach ($zonas as $zona) {
                DinamicaHasZone::create([
                    'dinamica_id' => $dinamica->ID_DINAMICA,
                    'zona_id' => $zona
                ]);
            }

            foreach ($venues as $venue) {
                DinamicaHasCenter::create([
                    'dinamica_id' => $dinamica->ID_DINAMICA,
                    'centro_id' => $venue
                ]);
            }

            $users = User::where('brand_id', $brand_id)->orWhere('rol', 0)->where('id', '!=', $user_id)->get();
            foreach ($users as $user) {
                $message = $autor->name . " " . $autor->last_name . " ha creado una nueva dinámica: " . $name;
                Notificacion::create([
                    'user_id' => $user->id,
                    'message' => $message
                ]);

                $this->email($user, $message, "Nueva dinámica creada");
            }

            return response()->json([
                'success' => true,
                'message' => 'Dínamica creada correctamente',
                'dinamica' => $dinamica
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
                // Aprobar o rechazar
                $dinamicas = Dinamica::with('zonas', 'centros')->whereIn('ID_DINAMICA', $request->get('dinamica_id'))->get();

                if ($action == 1) {
                    $zonas = [];
                    $centros = [];
                    foreach ($dinamicas as $dinamica) {
                        foreach ($dinamica->zonas as $zona) {
                            array_push($zonas, $zona->ID_MUNICIPIO);
                        }

                        foreach ($dinamica->centros as $centro) {
                            array_push($centros, $centro->ID_CENTRO);
                        }
                    }

                    $centros = Centro::whereIn('ID_MUNICIPIO', $zonas)->get();
                    $clientes = Usuario::whereIn('ID_CENTRO', $centros->pluck('ID_CENTRO'))
                        ->orWhereIn('ID_CENTRO', $centros->pluck('ID_CENTRO'))->get();

                    foreach ($dinamicas as $dinamica) {
                        foreach ($clientes as $cliente) {
                            $this->push($cliente, 'Te invitamos a participar en nuestra nueva dinámica ' . $dinamica->DINAMICA . ' y gana ' . $dinamica->PREMIO, 'Hay una nueva dinámica en tu zona');
                        }

                        $dinamica->ACTIVO = $action;
                        $dinamica->save();
                    }

                    return redirect()->back()->with('message', 'Dinámicas aprobadas correctamente!');
                } else {
                    return redirect()->back()->with('message', 'Dinámicas rechazadas correctamente!');
                }
            } else {
                // Eliminar
                Dinamica::whereIn('ID_DINAMICA', $request->get('dinamica_id'))->delete();
                return redirect()->back()->with('message', 'Dinámicas eliminadas correctamente!');
            }
        } catch (\Exception $ex) {
            Log::error("DevError Line " . $ex->getLine());
            Log::error("DevError File " . $ex->getFile());
            Log::error("Deverror Message " . $ex->getMessage());
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
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
