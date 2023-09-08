<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
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

        $europe = new DateTimeZone("Europe/Paris");
        $date = new DateTime();
        $heure = $date->setTimezone($europe);

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
                'code_verif' => $codeVerif,
                'date_verif' => $heure
                ]); 

        } else {

            return redirect('sign')->with("error", "Les mots de passe ne sont pas valide.");

        }
        
        if (!$user){
            
            return redirect('sign')->with("error", "Informations invalide.");

        } else {

            Mail::to($user)->send(new VerificationMailMailer($user));
            return redirect('sign')->with("info", "Votre compte a bien été enregistré ! Veuillez confirmer votre email en cliquant sur le lien qui vous a été envoyé par email.");

        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function confirmEmail(string $codeVerif){

        $user = User::where('code_verif', $codeVerif)->first();

        if ($user) {

                $europe = new DateTimeZone("Europe/Paris");
                $date = new DateTime();

                $maxVerifDate = $date->setTimeZone($europe);
                $maxVerifDate->modify('+1 day');

                if ($user->date_verif < $maxVerifDate) {

                    User::where('code_verif', $codeVerif)
                        ->where('is_verified', false)
                        ->update(['is_verified' => true, 'date_verif' => null , 'code_verif' => null]);

                    Auth::login($user);
                    return redirect('accueil/');

                } else {

                    $newCodeVerif = Str::random(64);
                    $heure = $date->setTimezone($europe);

                    User::where('code_verif', $codeVerif)
                        ->where('is_verified', false)
                        ->update(['is_verified' => false, 'date_verif' => $heure , 'code_verif' => $newCodeVerif]);

                    Mail::to($user)->send(new VerificationMailMailer($user));
                    return redirect('sign')->with("error", "La date de vérification de votre email a expériée. Un nouvel email vous a été envoyé.");

                }

            } else {

                return redirect('confirm')->with("error", "Aucun utilisateur n'existe avec ce code de vérification");

            }
    }
}
