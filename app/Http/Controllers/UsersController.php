<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Traits\NotificacionTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use NotificacionTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
    }

    public function api($brand_id = null)
    {
        try {
            if ($brand_id == null) {
                $users = User::with('brand')->get();
            } else {
                $users = User::where('brand_id', $brand_id)->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'success',
                'users' => $users
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
        if (Auth::user()->rol == 0) {
            return view('users.create_admin');
        }
        return view('users.create_gerente');
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
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'string|max:25|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'rol' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }

            if (Auth::user()->rol == 0) {
                if ($request->get('rol') == 4 || $request->get('rol') == 0) {
                    $brans_name = "N/A";
                    $brand_id = 0;
                } else {
                    if ($request->get('new_brand')) {
                        $brand = Brand::create(['BRAND' => $request->get('new_brand')]);
                        $brans_name = $brand->BRAND;
                        $brand_id = $brand->ID_BRAND;
                    } else {
                        $brand = Brand::where('ID_BRAND', $request->get('brand_id'))->first();
                        if ($brand) {
                            $brand_id = $brand->ID_BRAND;
                            $brans_name = $brand->BRAND;
                        } else {
                            $brand_id = Auth::user()->brand_id;
                            $brand = Brand::where('ID_BRAND', $brand_id)->first();
                            $brans_name = $brand->BRAND;
                        }
                    }
                }
            } else {
                $brand_id = Auth::user()->brand_id;
                $brand = Brand::where('ID_BRAND', $brand_id )->first();
                $brans_name = $brand->BRAND;
            }

            $request->flash();

            $user = User::create([
                'name' => htmlentities($request->get('name')),
                'email' => htmlentities($request->get('email')),
                'password' => Hash::make($request->get('password')),
                'last_name' => htmlentities($request->get('last_name')),
                'phone' => htmlentities($request->get('phone')),
                'rol' => $request->get('rol'),
                'brand_id' => $brand_id
            ]);

            switch ($request->get('rol')) {
                case 1:
                    $rol = "Gerente";
                    break;
                case 2:
                    $rol = "Supervisor";
                    break;
                case 3:
                    $rol = "Embajador";
                    break;
                case 4:
                    $rol = "Administrador - Tickets";
                    break;
                case 3:
                    $rol = "Administrador";
                    break;
                default:
                    $rol = "";
            }

            $message = "<br> Tu cuenta de 1.5Onzas se ha creado correctamente:<br><br>" .
                "<strong>Panel: </strong><a href='" . route('login') . "'>" . route('login') . "</a><br>" .
                "<strong>Usuario: </strong>" . $request->get('email') . "<br>" .
                "<strong>Contraseña: </strong>" . $request->get('password') . "<br>" .
                "<strong>Marca: </strong>" . $brans_name . "<br>" .
                "<strong>Acceso: </strong>" . $rol;

            $this->email($user, $message, "Bienvenido a 1.5Onzas");

            return redirect()->back()->with('message', 'Usuario creado correctamente');
        } catch (\Exception $ex) {
            Log::error("Deverror" . $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors(['message' => $ex->getMessage()]);
        }
    }

    public function adminView() {
        return view('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
