<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Desgloce;
use App\Dinamica;
use App\Marca;
use App\Municipio;
use App\Recibo;
use App\Usuario;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KpiController extends Controller
{
    public function index() {
        /*
        $brand_id = Auth::user()->brand_id;

        if ($brand_id != null && is_numeric($brand_id) && $brand_id > 0) {
            $dinamicas = Dinamica::select('marca_id', 'ID_BRAND')->where('ID_BRAND', $brand_id)->get();
            $marcas = Marca::select('ID_MARCA', 'MARCA')->whereIn('ID_MARCA', $dinamicas->pluck('ID_MARCA'))->get();
        } else {
            $marcas = Marca::select('ID_MARCA', 'MARCA')->where('ID_MARCA', "!=", 0)->get();
        }
        */

        $marcas = Marca::select('ID_MARCA', 'MARCA')->where('ID_MARCA', "!=", 0)->get();
        $centros = Centro::all();
        $zonas = Municipio::all();

        return view('kpi.kpi', compact('marcas', 'centros', 'zonas'));
    }

    function getData(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
            ]);

            $marca_id = $request->get('marca_id');
            $centro_id = $request->get('centro_id');
            $zona_id = $request->get('zona_id');

            if ($marca_id == null && ($centro_id == null && $zona_id == null)) {
                return response()->json(['success' => false, 'message' => "Selecciona un centro de consumo, zona o ubicación"], 401);
            }

            try {
                $fecha_inicio = Carbon::parse($request->get('fecha_inicio'));
                $fecha_fin = Carbon::parse($request->get('fecha_fin'));
            } catch (\Exception $exception) {
                return response()->json(['success' => false, 'message' => "Ingresa una fecha valida"], 401);
            }

            if ($marca_id != null && ($centro_id == null && $zona_id == null)) {
                /*
                 * Get por Marca
                 */

                $desgloces = Desgloce::where('ID_MARCA', $marca_id)->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])->groupBy('ID_RECIBO')->get();
                $recibos = Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')->whereIn('ID_RECIBO', $desgloces->pluck('ID_RECIBO'))->get();
                $usuarios = Usuario::select('ID_USUARIO', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'ACTIVO')->whereIn('ID_USUARIO', $recibos->pluck('ID_USUARIO'))->get();
                $centros = Centro::whereIn('ID_CENTRO', $recibos->pluck('ID_CENTRO'))->get();

                // Obtiene el mejor usuario con datos
                foreach ($usuarios as $usuario) {
                    $recibos_usuario = $recibos->where('ID_USUARIO', $usuario->ID_USUARIO);
                    $productos = $desgloces->whereIn('ID_RECIBO', $recibos_usuario->pluck('ID_RECIBO'));

                    $total = 0;
                    foreach ($productos as $producto) {
                        $total += $producto->CANTIDAD;
                    }

                    $usuario->total = $total;
                }

                $usuarios = $usuarios->sortByDesc(function ($usuario, $key) {
                    return $usuario->total;
                });

                $usuario = $usuarios->values()->first();

                // Obtiene el mejor centro con datos
                foreach ($centros as $centro) {
                    $recibos_centro = $recibos->where('ID_CENTRO', $centro->ID_CENTRO);
                    $productos = $desgloces->whereIn('ID_RECIBO', $recibos_centro->pluck('ID_RECIBO'));

                    $total = 0;
                    foreach ($productos as $producto) {
                        $total += $producto->CANTIDAD;
                    }

                    $centro->total = $total;
                }

                $centros = $centros->sortByDesc(function ($centro, $key) {
                    return $centro->total;
                });

                $centro = $centros->values()->first();

                return response()->json([
                    'success' => true,
                    'message' => 'success',
                    'usuario' => $usuario,
                    'centro' => $centro,
                    'usuarios' => $usuarios->values()->all(),
                    'centros' => $centros->values()->all()
                ], 200);

            } elseif ($marca_id == null && ($centro_id != null || $zona_id != null)) {
                /*
                 * Get por centro o zona
                 */
                if ($zona_id != null) {
                    $centros = Centro::where('ID_MUNICIPIO', $zona_id)->get();
                    $recibos = Recibo::
                        select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')
                        ->whereIn('ID_CENTRO', $centros->pluck('ID_CENTRO'))
                        ->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])
                        ->groupBy('ID_RECIBO')->get();
                    $desgloces = Desgloce::whereIn('ID_RECIBO', $recibos->pluck('ID_RECIBO'))->get();
                    $marcas = Marca::select('ID_MARCA', 'MARCA')->whereIn('ID_MARCA', $desgloces->pluck('ID_MARCA'))->get();
                    $usuarios = Usuario::select('ID_USUARIO', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'ACTIVO')
                        ->whereIn('ID_USUARIO', $recibos->pluck('ID_USUARIO'))->get();

                    // Obtiene el mejor usuario con datos
                    foreach ($usuarios as $usuario) {
                        $recibos_usuario = $recibos->where('ID_USUARIO', $usuario->ID_USUARIO);
                        $productos = $desgloces->whereIn('ID_RECIBO', $recibos_usuario->pluck('ID_RECIBO'));

                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $usuario->total = $total;
                    }

                    $usuarios = $usuarios->sortByDesc(function ($usuario, $key) {
                        return $usuario->total;
                    });

                    $usuario = $usuarios->values()->first();

                    foreach ($marcas as $marca) {
                        $productos = $desgloces->whereIn('ID_MARCA', $marca->ID_MARCA);
                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $marca->total = $total;
                    }

                    $marcas = $marcas->sortByDesc(function ($marca, $key) {
                        return $marca->total;
                    });

                    $marca = $marcas->values()->first();

                    return response()->json([
                        'success' => true,
                        'message' => 'success',
                        'usuario' => $usuario,
                        'marca' => $marca,
                        'usuarios' => $usuarios->values()->all(),
                        'marcas' => $marcas->values()->all()
                    ], 200);
                } else {
                    $recibos = Recibo::
                        select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')
                        ->where('ID_CENTRO', $centro_id)
                        ->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])
                        ->groupBy('ID_RECIBO')->get();
                    $desgloces = Desgloce::whereIn('ID_RECIBO', $recibos->pluck('ID_RECIBO'))->get();
                    $marcas = Marca::select('ID_MARCA', 'MARCA')->whereIn('ID_MARCA', $desgloces->pluck('ID_MARCA'))->get();
                    $usuarios = Usuario::select('ID_USUARIO', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'ACTIVO')
                        ->whereIn('ID_USUARIO', $recibos->pluck('ID_USUARIO'))->get();

                    // Obtiene el mejor usuario con datos
                    foreach ($usuarios as $usuario) {
                        $recibos_usuario = $recibos->where('ID_USUARIO', $usuario->ID_USUARIO);
                        $productos = $desgloces->whereIn('ID_RECIBO', $recibos_usuario->pluck('ID_RECIBO'));

                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $usuario->total = $total;
                    }

                    $usuarios = $usuarios->sortByDesc(function ($usuario, $key) {
                        return $usuario->total;
                    });

                    $usuario = $usuarios->values()->first();

                    foreach ($marcas as $marca) {
                        $productos = $desgloces->whereIn('ID_MARCA', $marca->ID_MARCA);
                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $usuario->total = $total;
                    }

                    $marcas = $marcas->sortByDesc(function ($marca, $key) {
                        return $marca->total;
                    });

                    $marca = $marcas->values()->first();

                    return response()->json([
                        'success' => true,
                        'message' => 'success',
                        'usuario' => $usuario,
                        'marca' => $marca,
                        'usuarios' => $usuarios->values()->all(),
                        'marcas' => $marcas->values()->all()
                    ], 200);
                }
            } else {
                /*
                 * Get por centro o zona y Marca
                 */
                if ($zona_id != null) {
                    $centros = Centro::where('ID_MUNICIPIO', $zona_id)->get();
                    $recibos = Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')->
                        whereIn('ID_CENTRO', $centros->pluck('ID_CENTRO'))
                        ->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])
                        ->groupBy('ID_RECIBO')->get();
                    $desgloces = Desgloce::whereIn('ID_RECIBO', $recibos->pluck('ID_RECIBO'))->where('ID_MARCA', $marca_id)->get();
                    $usuarios = Usuario::select('ID_USUARIO', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'ACTIVO')->whereIn('ID_USUARIO', $recibos->pluck('ID_USUARIO'))->get();
                    $centros = Centro::whereIn('ID_CENTRO', $recibos->pluck('ID_CENTRO'))->get();

                    // Obtiene el mejor usuario con datos
                    foreach ($usuarios as $usuario) {
                        $recibos_usuario = $recibos->where('ID_USUARIO', $usuario->ID_USUARIO);
                        $productos = $desgloces->whereIn('ID_RECIBO', $recibos_usuario->pluck('ID_RECIBO'));

                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $usuario->total = $total;
                    }

                    $usuarios = $usuarios->sortByDesc(function ($usuario, $key) {
                        return $usuario->total;
                    });

                    $usuario = $usuarios->values()->first();

                    // Obtiene el mejor centro con datos
                    foreach ($centros as $centro) {
                        $recibos_centro = $recibos->where('ID_CENTRO', $centro->ID_CENTRO);
                        $productos = $desgloces->whereIn('ID_RECIBO', $recibos_centro->pluck('ID_RECIBO'));

                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $centro->total = $total;
                    }

                    $centros = $centros->sortByDesc(function ($centro, $key) {
                        return $centro->total;
                    });

                    $centro = $centros->values()->first();

                    return response()->json([
                        'success' => true,
                        'message' => 'success',
                        'usuario' => $usuario,
                        'centro' => $centro,
                        'usuarios' => $usuarios->values()->all(),
                        'centros' => $centros->values()->all()
                    ], 200);
                } else {
                    $recibos = Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')
                        ->where('ID_CENTRO', $centro_id)
                        ->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])
                        ->groupBy('ID_RECIBO')->get();
                    $desgloces = Desgloce::whereIn('ID_RECIBO', $recibos->pluck('ID_RECIBO'))->where('ID_MARCA', $marca_id)->get();
                    $usuarios = Usuario::select('ID_USUARIO', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'ACTIVO')->whereIn('ID_USUARIO', $recibos->pluck('ID_USUARIO'))->get();

                    // Obtiene el mejor usuario con datos
                    foreach ($usuarios as $usuario) {
                        $recibos_usuario = $recibos->where('ID_USUARIO', $usuario->ID_USUARIO);
                        $productos = $desgloces->whereIn('ID_RECIBO', $recibos_usuario->pluck('ID_RECIBO'));

                        $total = 0;
                        foreach ($productos as $producto) {
                            $total += $producto->CANTIDAD;
                        }

                        $usuario->total = $total;
                    }

                    $usuarios = $usuarios->sortByDesc(function ($usuario, $key) {
                        return $usuario->total;
                    });

                    $usuario = $usuarios->values()->first();


                    return response()->json([
                        'success' => true,
                        'message' => 'success',
                        'usuario' => $usuario,
                        'usuarios' => $usuarios->values()->all(),
                    ], 200);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getLine() . $ex->getFile()], 500);
        }
    }

    function getMarcaData(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'marca_id' => 'required|numeric',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 401);
            }

            $marca_id = $request->get('marca_id');

            try {
                $fecha_inicio = Carbon::parse($request->get('fecha_inicio'));
                $fecha_fin = Carbon::parse($request->get('fecha_fin'));
            } catch (\Exception $exception) {
                return response()->json(['success' => false, 'message' => "Ingresa una fecha valida"], 401);
            }

            $desgloces = Desgloce::where('ID_MARCA', $marca_id)->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])->groupBy('ID_RECIBO')->get();
            $recibos = Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')->whereIn('ID_RECIBO', $desgloces->pluck('ID_RECIBO'))->get();
            $usuarios = Usuario::select('ID_USUARIO', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'ACTIVO')->whereIn('ID_USUARIO', $recibos->pluck('ID_USUARIO'))->get();
            $centros = Centro::whereIn('ID_CENTRO', $recibos->pluck('ID_CENTRO'))->get();

            // Obtiene el mejor usuario con datos
            foreach ($usuarios as $usuario) {
                $recibos_usuario = $recibos->where('ID_USUARIO', $usuario->ID_USUARIO);
                $productos = $desgloces->whereIn('ID_RECIBO', $recibos_usuario->pluck('ID_RECIBO'));

                $total = 0;
                foreach ($productos as $producto) {
                    $total += $producto->CANTIDAD;
                }

                $usuario->total = $total;
            }

            $usuarios = $usuarios->sortByDesc(function ($usuario, $key) {
                return $usuario->total;
            });

            $usuario = $usuarios->values()->first();

            // Obtiene el mejor centro con datos
            foreach ($centros as $centro) {
                $recibos_centro = $recibos->where('ID_CENTRO', $centro->ID_CENTRO);
                $productos = $desgloces->whereIn('ID_RECIBO', $recibos_centro->pluck('ID_RECIBO'));

                $total = 0;
                foreach ($productos as $producto) {
                    $total += $producto->CANTIDAD;
                }

                $centro->total = $total;
            }

            $centros = $centros->sortByDesc(function ($centro, $key) {
                return $centro->total;
            });

            $centro = $centros->values()->first();

            return response()->json([
                'success' => true,
                'message' => 'success',
                'usuario' => $usuario,
                'centro' => $centro,
                'usuarios' => $usuarios->values()->all(),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }

    function getCentroData(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'marca_id' => 'required|numeric',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'centro_id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 401);
            }

            $marca_id = $request->get('marca_id');
            $centro_id = $request->get('centro_id');

            try {
                $fecha_inicio = Carbon::parse($request->get('fecha_inicio'));
                $fecha_fin = Carbon::parse($request->get('fecha_fin'));
            } catch (\Exception $exception) {
                return response()->json(['success' => false, 'message' => "Ingresa una fecha valida"], 401);
            }

            $desgloces = Desgloce::where('ID_MARCA', $marca_id)
                ->whereBetween('FECHA', [$fecha_inicio, $fecha_fin])
                ->groupBy('ID_RECIBO')->get();
            $recibos = Recibo::select('ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA', 'status')
                ->whereIn('ID_RECIBO', $desgloces->
                pluck('ID_RECIBO'))->where('ID_CENTRO', $centro_id)->get();

            $dates = [];

            for($date = $fecha_inicio; $date->lte($fecha_fin); $date->addDay()) {
                $start = $date;
                $end = $date;
                $dates[] = [
                    'start' => $start->startOfDay()->format('d-m-Y H:i:s'),
                    'end' => $end->endOfDay()-> format('d-m-Y H:i:s'),
                ];
            }

            $dates = collect($dates);

            $fechas = [];
            foreach ($dates as $date) {
                $recibos_date = $recibos->filter(function ($item) use ($date) {
                    return (data_get($item, 'FECHA') > Carbon::parse($date['start'])) &&
                        (data_get($item, 'FECHA') < Carbon::parse($date['end']));
                });

                $productos = $desgloces->whereIn('ID_RECIBO', $recibos_date->pluck('ID_RECIBO'));

                $total = 0;
                foreach ($productos as $producto) {
                    $total += $producto->CANTIDAD;
                }

                array_push($fechas, [
                    'fecha' => Carbon::parse($date['start'])->format('d-m-Y'),
                    'total' => $total]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'success',
                'dates' => $fechas
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => "Error, código 500"], 500);
        }
    }
}
