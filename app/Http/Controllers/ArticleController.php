<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(){

        if (Auth::check()) {

           $article = DB::table('articles')->get();

            return view('article', ['article' => $article]);

        } else {
            
            return redirect('/');
        }
        
    }

    public function add()
    {
        return view('articleAdd', []);

    }

    public function addPost(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:16000'
        ]);
        
        $article = Article::where('title', $request->title)->first();

        if (isset($article) === false) {

            $article = Article::create([ 
                'content' => $request->content,
                'title' => $request->title
                ]);

        } else {

            return redirect('articleAdd')->with("error", "Un article avec ce titre existe déjà");

        }
        
    }
}
