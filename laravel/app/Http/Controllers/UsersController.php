<?php

namespace App\Http\Controllers;

use App\User;
use App\UsersCompanies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $datos["users"]=DB::table('users')->get();
        return view('company.adminUsers',  $datos);
    }
     public function store(Request $request) {
        $c = $request->except('_token');
    // Start transaction!
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $c["password"] = Hash::make($c["password"]);
          
            $c["email_verified_at"] = date("Y-m-d H:i:s");  
            $bo = User::create($c);
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return Redirect::to('/form')
                            ->withErrors($e->getErrors())
                            ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return Redirect::back()->with('message', 'Usuario ingrasado con exito!!');
    }
    public function addAccountant(Request $request) {
        $u = $request->except('_token');
        // Start transaction!
        DB::beginTransaction();
        try {
            $u['email_verified_at'] = now();
            // Validate, then create if valid
            $user = User::where('email',$u['email'])->first();
            if ($user === null) {
                $u["password"] = Hash::make($u["password"]);
                $user = User::create($u);
            }
            
            $data["id_company"]=session('company')->id;
            $data["id_user"]=$user["id"];
            $data["roll"]=$u["roll"];
            $data["active"]=1;
            $uc = UsersCompanies::create($data);
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return Redirect::to('/form')
                            ->withErrors($e->getErrors())
                            ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return Redirect::back()->with('message', 'Usuario ingrasado con exito!!');
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function changeStateUser($id,$state) {
        $data ["active"] = $state;
        User::where('id', '=', $id)->update($data);
        return $state;
    }
    
    public function changeRollUser($roll,$id) {
        $data ["roll"] = $roll;
        User::where('id', '=', $id)->update($data);
        return $roll;
    }
     public function edit($id) {
        return User::findOrFail($id);
    }
    public function update(Request $request, $id) {
        $c = $request->except("_token", "_method");
        User::where('id', '=', $id)->update($c);
        return Redirect::back()->with(['message'=> 'Usuario actualizado con exito!!']);
    }
    public function destroy($id) {
        $uc = UsersCompanies::where("id_company",session('company')->id)
        ->where("id_user",$id)->first();
        $uc->delete($uc->id);
        
        return Redirect::back()->with(['message'=> 'Usuario eliminado con exito!!','tab'=>'profile-user']);
    }
   
}
