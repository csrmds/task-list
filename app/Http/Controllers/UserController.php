<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Route;

class UserController extends Controller {

    private $user;

    public function __construct() {
        $this->user= new User;
    }


    public function getUserById(Request $request) {

    }

    public function getUsers(Request $request) {

    }


    public function setUser($data) {
        if ($data['google_id']==null) {
            $this->user->name= $data['name'];
            $this->user->last_name= $data['last_name'];
            $this->user->email= $data['email'];
            $this->user->password= $data['password'];
            $this->user->avatar= $data['avatar'];
        } else {
            $this->user->name= $data['name'];
            $this->user->last_name= $data['last_name'];
            $this->user->email= $data['email'];
            $this->user->password= $data['google_id'];
            $this->user->google_id= $data['google_id'];
            $this->user->avatar= $data['avatar'];
        }
    }

    public function store(Request $request) {
        logger("store user request: ", $request->all());


        try {
            $userData= $request->input('userData');
            $this->setUser($userData);
            $this->user->updateOrCreate();
            
            return response()->json([
                'success'=> true,
                'message'=> 'Usuário salvo com sucesso',
                'data'=> $this->user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao tentar salvar a conta de usuário',
                'data'=> null,
                'error'=> $e->getMessage()
            ]);
        }

    }

    public function authCallback(Request $request) {
        
        try {            
            $googleUser= Socialite::driver('google')->stateless()->with(['prompt' => 'select_account'])->user();

            $user= User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Auth::login($user);

            } else {
                $this->user->name= $googleUser->getName();
                $this->user->google_id= $googleUser->getId();
                $this->user->email= $googleUser->getEmail();
                $this->user->avatar= $googleUser->getAvatar();
                $this->user->password= $googleUser->getId();

                $this->user->save();

                Auth::login($this->user);
            }

            
            return redirect('/')->with([
                'success'=> true,
                'userData'=> Auth::user()->email,
                'message'=> "qualquer mensagem"
            ]);
        } catch(Exception $e) {
            logger("login error: ", [$e->getMessage()]);

            return redirect('/login')->withErrors(['google'=> 'erro ao autenticar com o Google']);
        }

    }

    public function logout() {

        try {
            Auth::logout();
            return redirect('/login')->with([
                'success'=> true,
                'message'=> "logoff feito com sucesso",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao fazer logoff',
                'error'=> $e->getMessage()
            ]);
        }

    }

    public function update(Request $request) {

    }

    public function destroy(Request $request) {

    }


}