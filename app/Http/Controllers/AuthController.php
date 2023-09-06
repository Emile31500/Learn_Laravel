<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
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

        return view('sign')->with(['codeVerification' => 'string']);

    }

    public function loginPost(Request $request) 
    {
        $request->validate([
            'email' => 'required|max:255',
            'password' => 'required|max:255'
        ]);
        
        $user = User::where('email', $request->email)->first();
        var_dump($user);
        die;

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
        $codeVerif = Str::random(63);

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
                'password' => Hash::make($request->password),
                'code_verif' => $codeVerif
                ]); 

        } else {

            return redirect('sign')->with("error", "Les mots de passe ne sont pas valide.");

        }
        
        if (!$user){
            
            return redirect('sign')->with("error", "Informations invalide.");

        } else {

            Mail::to($user)->send(new VerificationMailMailer($user));
            return redirect('accueil/')->with([]);

        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function confirmEmail(string $codeVerif){

        $user = User::where('code_verif', $codeVerif)->first();
        // var_dump($user);
        // die;

        if ($user) {

            User::where('code_verif', $codeVerif)
                ->where('is_verified', false)
                ->update(['is_verified' => true, 'code_verif' => null]);

            Auth::login($user);
            return redirect('accueil/');

        } else {

            return redirect('confirm')->with("error", "Aucun utilisateur n'existe avec ce code de vérification");

        }

    }
}
