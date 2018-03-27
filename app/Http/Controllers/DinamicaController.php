<?php

namespace App\Http\Controllers;

use App\Dinamica;
use App\Notificacion;
use App\Traits\NotificacionTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DinamicaController extends Controller
{
    use NotificacionTrait;
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
                $dinamicas = Dinamica::all();
            } else {
                $dinamicas = Dinamica::where('ID_BRAND', $brand_id)->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'success',
                'dinamicas' => $dinamicas
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
                'venue' => 'required|numeric',
                'zona' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->first()], 401);
            }

            $brand_id = $request->get('brand_id');
            $user_id = $request->get('user_id');
            $autor = User::whereId($user_id)->first();
            $cantidad = -1;
            $descripción = $request->get('reglas');
            $marca = 0;
            if ($request->get('kind') == 1) {
                $cantidad = $request->get('cantidad');
                $descripción = $request->get('descripcion');
                $marca = $request->get('marca');
            }

            if ($descripción == null) {
                return response()->json(['success' => false, 'message' => "Ingresa las reglas de la dinámica"], 401);
            }

            $dinamica = Dinamica::create([
                'ID_ZONA' => 0,
                'ID_BRAND' => $brand_id,
                'OBJETIVO' => $cantidad,
                'DINAMICA' => $request->get('name'),
                'DESCRIPCION' => $descripción,
                'PREMIO' => $request->get('premio'),
                'FECHA_INICIO' => Carbon::parse($request->get('start')),
                'FECHA_FIN' => Carbon::parse($request->get('end')),
                'ACTIVO' => 0,
                'municipio_id' => $request->get('zona'),
                'marca_id' => $marca,
                'user_id' => $user_id
            ]);

            $users = User::where('brand_id', $brand_id)->orWhere('rol', 0)->where('id', '!=', $user_id)->get();
            foreach ($users as $user) {
                $message = $autor->name . " " . $autor->last_name . " ha creado una nueva dinámica: " . $request->get('name');
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
            return response()->json(['success' => false, 'message' => "Error, código 500" . $ex->getMessage() . $ex->getFile() . $ex->getLine()], 500);
        }
    }

    public function action(Request $request) {
        try {
            $action = $request->get('actions');
            if ($action == 1 || $action == 2) {
                // Aprobar o rechazar
                Dinamica::whereIn('ID_DINAMICA', $request->get('dinamica_id'))->update(['ACTIVO' => $action]);
            } else {
                // Eliminar
                Dinamica::whereIn('ID_DINAMICA', $request->get('dinamica_id'))->delete();
            }

            return redirect()->back()->with('message', 'Dinámicas actualizas');
        } catch (\Exception $ex) {
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
