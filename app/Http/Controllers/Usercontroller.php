<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return response()->json([
            "message" => "liste des utilisateurs",
            "data" => $users
        ], 200);
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
        $request->validate(
            [
                "name"=>"required",
                "password"=>"required|min:6",
            ]
        );
       // $user = User::create($request->all());
       if(isset($request->name) && isset($request->password)){ 
         $user = new User();
         $user->name = $request->name;
         $user->password = Hash::make($request->password);

         $user->save();

         return response()->json([
            "message"=>"utilisateur bien cree",
            "data"=>$user
         ], 200);
       } else {
        return response()->json([
            "message"=>"echec de l'enregistrement",
        ], 404); //
       }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::find($id);
        if(isset($user)) {
            return response()->json([
                "message"=>"utilisateur trouvé",
                "data"=>$user
            ],200);
        } else {
            return response()->json([
                "message"=>"utilisateur introuvable",
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate(
            [
                "name"=>"required",
                "password"=>"required|min:6",
            ]
        );
        $user = User::find($id);
        if(isset($user)) {
            $user->update($request->all());
            return response()->json([
                "message"=>"modification reussi",
                "data"=>$user
            ], 200);
        } else {
            $user->update($request->all());
            return response()->json([
                "message"=>"utilisateur introuvable",
            ], 404);
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
        $user = User::find($id);
        $userCopy = $user;

        if(isset($user)) {
            $user->delete();

         return response()->json([
                "message"=>"utilisateur supprimé",
                "data"=>$userCopy
        ], 404);
        }

        return response()->json([
            "message"=>"utilisateur introuvable",
        ], 404);
    }

    public function login(Request $request) {

       $attrs = $request->validate(
            [
                "name"=>"required",
                "password"=>"required|min:6",
            ]
        );

        if(isset($request->name) && isset($request->password)){
            $checked = User::where('name', $request->name)->first();

            if(!$checked){

                return response()->json([
                    "error" => "error",
                    "message" => "impossible de connecter le user"
                ], 404);

            } else{

                if(!Auth::attempt($attrs)) {
                    return response()->json([
                        "error" => "error",
                        "message" => "impossible de connecter le user"
                    ], 404);
                }

                return response()->json([
                    "message" => "connection reussie",
                    "data" => $checked
                ], 200);
            }
        }
    }
}


