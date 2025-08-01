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

    public function authGoogle(Request $request) {

        return Socialite::driver('google')
        ->scopes([
            'openid',
            'https://www.googleapis.com/auth/calendar',
        ])
        ->with(['prompt' => 'select_account'])
        ->redirect();
        
    }

    public function authCallback(Request $request) {
        
        try {            
            $googleUser= Socialite::driver('google')->stateless()->user();

            $user= User::updateOrCreate(
                ['google_id'=> $googleUser->getId()],
                [
                    'name'=> $googleUser->getName(),
                    'email'=> $googleUser->getEmail(),
                    'avatar'=> $googleUser->getAvatar(),
                    'access_token'=> $googleUser->token,
                    'refresh_token'=> $googleUser->refreshToken,
                    'token_expires_in'=> $googleUser->expiresIn,
                ]
            );

            Auth::login($user);
            
            return redirect('/')->with([
                'success'=> true,
                'userData'=> Auth::user()->email,
                'message'=> "Autenticação realizada com sucesso"
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

    public function login(Request $request) {

        try {

            $credentials= $request->validate([
                'email'=> ['required', 'email'],
                'password'=> ['required']
            ]);


            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return response()->json([
                    'success'=> true,
                    'message'=> "Login feito com sucesso",
                ]);
            } else {
                return response()->json([
                    'success'=> false,
                    'message'=> "Usuário ou senha inválidos"
                ]);
            }

            
        } catch(Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao fazer login',
                'error'=> $e->getMessage()
            ]);
        }

    }

    public function update(Request $request) {

    }

    public function destroy(Request $request) {

    }


}