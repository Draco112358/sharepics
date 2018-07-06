<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Storage;


class PhotosController extends Controller
{

    protected $regoleStore = [
        'album_id' => 'required|integer|exists:albums,id',
        'name' => 'required',
        'description' => 'required',
        'img_path' => 'required|image'
    ];

    protected $regoleUpdate = [
        'album_id' => 'required|integer|exists:albums,id',
        'name' => 'required',
        'description' => 'required',

    ];


    protected $messaggiErrore = [
        'album_id.required' => 'Seleziona un album in cui inserire la foto',
        'name.required' => 'La foto deve avere un nome',
        'description.required' => 'Inserisci una descrizione',
        'img_path.required' => 'Seleziona una foto'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');

        // Essendo un Resource controller possiamo usare questo metodo che mappa automaticamente le policy.
        $this->authorizeResource(Photo::class);
    }

    public function index()
    {
        $photos = Photo::orderBy('album_id', 'desc')->orderBy('id', 'desc')
            ->with('album')->get();
        $photos = $photos->filter(function ($photo){
            return $photo->user != null;
        });
        $this->authorize('view', Photo::class);
            return view('admin.adminphotos')->with('photos', $photos);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        //$albumID = $req->has('album_id')? $req->input('album_id') : null;
        $album = Album::firstOrNew(['id' => $req->input('album_id')]);

        $photo = new Photo();
        $albums = $this->getAlbums();

        $numAlbums = $albums->count();

        if ($numAlbums >0){
            $ultimoAlbum = $albums->first();
            return view('immagini.editimmagine')->with([
                'photo' => $photo,
                'album' => $album,
                'albums' => $albums,
                'user' => Auth::user(),
                'numeroAlbums' => $numAlbums,
                'lastAlbum' => $ultimoAlbum
            ]);
        }
        else{
            return view('immagini.editimmagine')->with([
                'photo' => $photo,
                'album' => $album,
                'albums' => $albums,
                'user' => Auth::user(),
                'numeroAlbums' => $numAlbums,
                'lastAlbum' => null
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $this->validate($request, $this->regoleStore, $this->messaggiErrore);

       $photo = new Photo();

       $photo->name = $request->input('name');
       $photo->description = $request->input('description');
       $photo->album_id = $request->input('album_id');
       $photo->img_path = '';
       $photo->img_thumbnail = '';
       $photo->save();
       $this->fileProcessing($photo);
       $photo->save();
       return redirect(route('album.showimages', $photo->album_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //dd($photo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)  // Perché funzioni il model binding dobbiamo chiamare il
                                        // parametro come laravel ha impostato nella rotta.
    {
        $albums = $this->getAlbums();
        $album = $photo->album;
        if (\request()->has('adminpanel')){
            return view('admin.adminphotos_edit')->with(['photo' => $photo, 'albums' => $albums, 'album' => $album]);
        }
        $numAlbums = $albums->count();
        if ($numAlbums >0) {
            $ultimoAlbum = $albums->first();
            return view('immagini.editimmagine')->with([
                'photo' => $photo,
                'albums' => $albums,
                'album' => $album,
                'user' => Auth::user(),
                'numeroAlbums' => $numAlbums,
                'lastAlbum' => $ultimoAlbum
            ]);
        }
        else{
            return view('immagini.editimmagine')->with([
                'photo' => $photo,
                'albums' => $albums,
                'album' => $album,
                'user' => Auth::user(),
                'numeroAlbums' => $numAlbums,
                'lastAlbum' => null
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $this->validate($request, $this->regoleUpdate, $this->messaggiErrore);

        if (stristr($photo->img_thumbnail, 'http') != true) {
            $this->fileProcessing($photo);
        }
        $photo->name = $request->input('name');
        $photo->description = $request->input('description');
        $photo->album_id = $request->input('album_id');
        $res = $photo->save();
        $messaggio = $res ? 'Foto '.$photo->name.' modificata' : 'Foto non modificata';
        session()->flash('message', $messaggio);

        if ($request->has('adminpanel')){
            return redirect()->route('photos.list');
        }
        return redirect()->route('album.showimages',$request->input('album_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $photo corrisponde all'id della foto con cui laravel fa il model binding
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Photo $photo) // Perché funzioni il model binding dobbiamo chiamare il
                                          // parametro come laravel ha impostato nella rotta.
    {
        $photo->comments()->delete();
        $path = $photo->img_path;
        $pathumb = $photo->img_thumbnail;
        $imagesRoot = env('IMG_DIR').'/'.$photo->album_id;
        $imagesThumbsRoot = env('IMG_THUMBS_DIR').'/'.$photo->album_id;
        $res = $photo->delete();

        if ($res){
            if ($path && Storage::disk('public')->has($path)){
                Storage::disk('public')->delete($path);
            }
            if ($pathumb && Storage::disk('public')->has($pathumb)){
                Storage::disk('public')->delete($pathumb);
            }
            if (\File::isDirectory(Storage::disk('public')->path($imagesRoot)) && $this->check_empty_folder(Storage::disk('public')->path($imagesRoot))){
                //\File::deleteDirectory(Storage::disk('public')->path($imagesRoot));
                Storage::disk('public')->deleteDirectory($imagesRoot);
            }
            if (\File::isDirectory(Storage::disk('public')->path($imagesThumbsRoot)) && $this->check_empty_folder(Storage::disk('public')->path($imagesThumbsRoot))){
               // \File::deleteDirectory(Storage::disk('public')->path($imagesThumbsRoot));
                Storage::disk('public')->deleteDirectory($imagesThumbsRoot);
            }

        }
        return ''.$res;
    }


    public function fileProcessing(Photo $photo, Request $req = null)
    {

        if (!$req){
            $req = \request();
        }
        /*
         * ######################## NUOVA IMMAGINE ######################################################
         */
        if (!$req->input('old_albumID')) {

            if (!$req->hasFile('img_path') || !$req->file('img_path')->isValid()){
                return false;
            }

            $file = $req->file('img_path');

            $fileName = $photo->id . '.' . $file->extension();


            $img = Image::make($file->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });



            $req->file('img_path')->storeAs(env('IMG_DIR') . '/' . $photo->album_id, $fileName, 'public');
            if (!\File::isDirectory(Storage::disk('public')->path(env('IMG_THUMBS_DIR') . '/' . $photo->album_id))) {
                Storage::disk('public')->makeDirectory(env('IMG_THUMBS_DIR') . '/' . $photo->album_id);
            }
            $img->save(storage_path('app/public/' . env('IMG_THUMBS_DIR') . '/' . $photo->album_id . '/' . $fileName), 80);
            $photo->img_path = env('IMG_DIR') . '/' . $photo->album_id . '/' . $fileName;
            $photo->img_thumbnail = env('IMG_THUMBS_DIR') . '/' . $photo->album_id . '/' . $fileName;
            return true;

            /*
             * ####################################### MODIFICA IMMAGINE ############################################à
             */
        }elseif ($req->input('old_albumID') !== $req->input('album_id')){

            $fileName = $photo->id . '.' . pathinfo($photo->img_path, PATHINFO_EXTENSION);
            $newImgPath = storage_path('app/public/'.env('IMG_DIR').'/'.$req->input('album_id'));
            $newThumbsPath = storage_path('app/public/'.env('IMG_THUMBS_DIR').'/'.$req->input('album_id'));
            if (!\File::isDirectory($newImgPath)) {
                \File::makeDirectory($newImgPath);
            }
            if (!\File::isDirectory($newThumbsPath)) {
                \File::makeDirectory($newThumbsPath);
            }
            \File::move(storage_path('app/public/' . $photo->img_path), $newImgPath.'/'.$fileName);
            \File::move(storage_path('app/public/'.$photo->img_thumbnail), $newThumbsPath.'/'.$fileName);

            $photo->img_path = env('IMG_DIR').'/'.$req->input('album_id').'/'.$fileName;
            $photo->img_thumbnail = env('IMG_THUMBS_DIR').'/'.$req->input('album_id').'/'.$fileName;

            $imagesRoot = env('IMG_DIR').'/'.$req->input('old_albumID');
            $imagesThumbsRoot = env('IMG_THUMBS_DIR').'/'.$req->input('old_albumID');
            if (\File::isDirectory(Storage::disk('public')->path($imagesRoot)) && $this->check_empty_folder(Storage::disk('public')->path($imagesRoot))){
                \File::deleteDirectory(Storage::disk('public')->path($imagesRoot));
            }
            if (\File::isDirectory(Storage::disk('public')->path($imagesThumbsRoot)) && $this->check_empty_folder(Storage::disk('public')->path($imagesThumbsRoot))){
                \File::deleteDirectory(Storage::disk('public')->path($imagesThumbsRoot));
            }
        }
    }


    public function getAlbums(){
        if (\request()->has('adminpanel')){
            return Album::orderBy('album_name')->get();
        }
        return Album::orderBy('id', 'desc')->where('user_id', \Auth::user()->id)->get();
    }


    protected function check_empty_folder ( $folder )
    {
        $files = array ();
        if ( $handle = opendir ( $folder ) ) {
            while ( false !== ( $file = readdir ( $handle ) ) ) {
                if ( $file != "." && $file != ".." ) {
                    $files [] = $file;
                }
            }
            closedir ( $handle );
        }
        return ( count ( $files ) > 0 ) ? FALSE : TRUE;
    }

}
