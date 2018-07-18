<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Category;
use App\Models\Photo;
use App\Policies\AlbumPolicy;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    protected $numAlbumForGuest = 9;


    public function index(){
        $dati = $this->statistics();


        if(Auth::check() || $this->numAlbumForGuest > $dati['numAlbums']) {
            $albums = Album::has('user')->latest('id')->with('categories')->paginate(9);
        }else{
            $collections = Album::has('user')->latest('album_name')->with('categories')->get();
            $collections = $collections->chunk($this->numAlbumForGuest);

            $albums = $collections->first();
        }
        $categories = Category::withCount('albums')->get();
        if (isset($dati['photoMaxRated'])) {
            return view('gallery.gallery-albums')
                ->with([
                    'albums' => $albums,
                    'cats' => $categories,
                    'numUtenti' => $dati['numUtenti'],
                    'numAlbums' => $dati['numAlbums'],
                    'lastAlbum' => $dati['lastAlbum'],
                    'immagine' => $dati['photoMaxRated'],
                    'valutazione' => $dati['maxRating'],
                    'ownerPhoto' => $dati['ownerPhoto']
                ]);
        }
        else{
            return view('gallery.gallery-albums')
                ->with([
                    'albums' => $albums,
                    'cats' => $categories,
                    'numUtenti' => $dati['numUtenti'],
                    'numAlbums' => $dati['numAlbums'],
                    'lastAlbum' => $dati['lastAlbum']
                ]);
        }
    }

    public function showAlbumPhotos(Album $album){
        if (!Auth::check()){
            $collections = Album::has('user')->latest('album_name')->with('categories')->get();
            $collections = $collections->chunk($this->numAlbumForGuest);
            $albums = $collections->first();
            if (!$albums->contains('id', $album->id)){
                abort(401);
            }
        }
        $user = $album->user;
         $numPhotos= count($album->photos);
         $numDefault = 15;
        if (Auth::check() || $numDefault > $numPhotos){
            $immagini = $album->photos->paginate(20);
        }
        else{
            $images = $album->photos->chunk($numDefault);
            $immagini = $images->first();
        }

        //if (!Auth::check() && $numPhotos > $numDefault) {
          //  $immagini = $immagini->random($numDefault);
        //}
        //$immagini->paginate(30);

        //$immagini = Photo::whereAlbumId($album->id)->latest()->paginate(30);
        return view('gallery.gallery-immagini')->with([
            'immagini'=> $immagini,
            'album' => $album,
            'proprietario' => $user,
            'numeroDiFoto' => $numPhotos
        ]);
    }

    public function showCategoryAlbums(Category $id)
    {
        $dati = $this->statistics();
        $categories = Category::withCount('albums')->get();
        if ($dati['photoMaxRated']) {
            return view('gallery.gallery-albums')
                ->with([
                    'albums' => $id->albums->paginate(9),
                    'cats' => $categories,
                    'numUtenti' => $dati['numUtenti'],
                    'numAlbums' => $dati['numAlbums'],
                    'lastAlbum' => $dati['lastAlbum'],
                    'immagine' => $dati['photoMaxRated'],
                    'valutazione' => $dati['maxRating'],
                    'ownerPhoto' => $dati['ownerPhoto']
                ]);
        }
        else{
            return view('gallery.gallery-albums')
                ->with([
                    'albums' => $id->albums->paginate(9),
                    'cats' => $categories,
                    'numUtenti' => $dati['numUtenti'],
                    'numAlbums' => $dati['numAlbums'],
                    'lastAlbum' => $dati['lastAlbum']
                ]);
        }
    }

    private function statistics(){
        $data['numUtenti'] = User::count();
        $data['numAlbums'] = Album::has('user')->count();

        $data['lastAlbum'] = Album::has('user')->with('user')->orderBy('id', 'desc')->first();

        $photos = Photo::get()->filter(function ($photo){
            return $photo->user != null;
        });
        if($photos != null) {
            foreach ($photos as $photo) {
                $photoRate[$photo->id] = $photo->averageRate() ? $photo->averageRate() : 0.0;
            }
            if (isset($photoRate)) {
                $photoMaxRated = Photo::findOrFail(array_keys($photoRate, max($photoRate)))[0];
                $maxRating = round($photoMaxRated->averageRate(), 1);

                if ($maxRating != 0.0) {
                    $data['photoMaxRated'] = $photoMaxRated;
                    $data['maxRating'] = $maxRating;
                    $data['ownerPhoto'] = $data['photoMaxRated']->user->name;
                }
            }
        }

        return $data;

    }
}