<?php

namespace App\Http\Controllers\Admin;

use Actuallymab\LaravelComment\Models\Comment;
use App\Models\Album;
use App\Models\Category;
use App\Models\Photo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Dati statistici nella dashboard dell'amministratore
     */
    public function index()
    {
        $users = User::all();

        $dati['utentiTotali'] = $users->count();
        $dati['utentiDisattivati'] = $users->filter(function ($user){
            return $user->deleted_at != null;
        })->count();
        $dati['utentiAttivi'] = $dati['utentiTotali'] - $dati['utentiDisattivati'];

        $dati['albumsTotali'] = Album::count();
        $dati['fotoTotali'] = Photo::count();
        $dati['categorie'] = Category::count();
        $dati['commenti'] = Comment::count();
        return view('admin.admindashboard')->with('dati', $dati);
    }
}
