<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VerificationMailMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        return view('login');
    }

    public function sign(Request $request)
    {

        return view('sign');

    }

    public function loginPost(Request $request) 
    {
        $request->validate([
            'email' => 'required|max:255',
            'password' => 'required|max:255'
        ]);
        
        $user = User::where('email', $request->email)->first();

        if (!$user) {

            return redirect('login')->with("error", "L'email et/ou le mot de passe est érroné");

        } else if (Hash::check($request->password, $user->password)) {

            Auth::login($user);
            return redirect('accueil/');

        } else {

            return redirect('login')->with("error", "L'email et/ou le mot de passe est érroné");

        }

    }

    public function signPost(Request $request)
    {
        
        $request->validate([
            'email' => 'required|unique:users|max:255',
            'name' => 'required|max:255',
            'password' => 'required|max:255',
            'confPassword' => 'required|max:255'
        ]);

        if ($request->password === $request->confPassword){


            $user = User::create([ 
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password)
                ]);

        } else {

            return redirect('sign')->with("error", "Les mots de passe ne sont pas valide.");

        }
        
        if (!$user){
            
            return redirect('sign')->with("error", "Informations invalide.");

        } else {

            //Mail::to($request->user())->send(new VerificationMailMailer($user));
            Mail::to($user)->send(new VerificationMailMailer($user));
            

            // Auth::login($user);
            return redirect('accueil/');

        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
