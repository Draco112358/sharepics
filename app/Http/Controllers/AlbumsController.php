<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumEditRequest;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Models\Category;
use App\Models\Photo;
use App\User;
use ClassesWithParents\D;
use Doctrine\Common\Collections\AbstractLazyCollection;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use function Sodium\increment;
use DB;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AlbumsController extends Controller
{
    public function index(Request $request){

        /*
         *      ELOQUENT
         * Il primo metodo che invochiamo in un model deve essere statico.
         */
        $queryBuilder = Album::orderBy('id', 'desc')->withCount('photos')
            ->with('categories')->where('user_id', Auth::user()->id);;
        $numAlbums = $queryBuilder->count();



        if ($request->has('id')){
            $queryBuilder->where('id', '=', $request->input('id'));
        }
        if ($request->has('album_name')){
            $queryBuilder->where('album_name', 'like', $request->input('album_name').'%');
        }
        $queryBuilder2 = $queryBuilder;
        $albums = $queryBuilder->paginate(15);

            if ($numAlbums > 0) {
                $ultimoAlbum = $queryBuilder2->first();
                return view('albums.albums')
                    ->with([
                        'albums' => $albums,
                        'numeroAlbums' => $numAlbums,
                        'lastAlbum' => $ultimoAlbum,
                        'user' => Auth::user()
                    ]);
            }
            else{
                return view('albums.albums')
                    ->with([
                        'albums' => $albums,
                        'numeroAlbums' => $numAlbums,
                        'lastAlbum' => null,
                        'user' => Auth::user()
                    ]);
            }




        /*
         *      METODO CON QUERYBUILDER
         *
        $queryBuilder = DB::table('albums')->orderBy('id', 'desc');


        if ($request->has('id')){
            $queryBuilder->where('id', '=', $request->input('id'));
        }

        if ($request->has('album_name')){
            $queryBuilder->where('album_name', 'like', $request->input('album_name').'%');
        }

        $albums = $queryBuilder->get();
        return view('albums.albums', ['albums' => $albums]);
        */



        /*
         *     METODO QUERY GREZZE
         *
        $sql = 'select * from albums where 1=1';
        $where = [];
        if ($request->has('id')){
            $where['id'] = $request->get('id');
            $sql .= ' AND id=:id';
        }
        if ($request->has('album_name')){
            $where['album_name'] = $request->get('album_name');
            $sql .= ' AND album_name=:album_name';
        }

        $sql .= ' order by id desc';

        //dd($sql);
        $albums = DB::select($sql, $where);
        return view('albums.albums', ['albums' => $albums]);
        */
    }


    public function delete(Album $id){//indicando il parametro
        //in questo modo, laravel va a cercarsi in automatico l'Album con primary key $id e l'associa
        // alla variabile $id. L'importante è che il parametro id sia anche quello passato nella rotta.

        /*
         *    ELOQUENT
         */
        $thumb = $id->album_thumbnail;
        $thumb2 = $id->album_thumbnail2;
        $album_images = env('IMG_DIR').'/'.$id->id;
        $images_thumbs = env('IMG_THUMBS_DIR').'/'.$id->id;

        $photos = $id->photos;
        if ($photos != null){
            foreach ($photos as $photo){
                $photo->comments()->delete();
            }
        }

        $res = $id->delete();

        if ($res){
            if ($thumb && Storage::disk('public')->has($thumb)){
                Storage::disk('public')->delete($thumb);
            }
            if ($thumb2 && Storage::disk('public')->has($thumb2)){
                Storage::disk('public')->delete($thumb2);
            }
            if (\File::isDirectory(Storage::disk('public')->path($album_images))){
                \File::deleteDirectory(Storage::disk('public')->path($album_images));
            }
            if (\File::isDirectory(Storage::disk('public')->path($images_thumbs))){
                \File::deleteDirectory(Storage::disk('public')->path($images_thumbs));
            }
        }
        return ''.$res;   // abbiamo concatenato una stringa, dovendo ritornare una stringa per funzionare.

        /*
         *    ELOQUENT tramite querybuilder
         *
        $res = Album::where('id', $id)->delete();
        return $res;
        */

        /*
         *    METODO CON QUERYBUILDER
         *
        $res = DB::table('albums')->where('id', $id)->delete();
        return $res;
        */

        /*
         *     METODO QUERY GREZZE
         *
        $sql = 'DELETE FROM albums WHERE id = :id';
        return DB::delete($sql, ['id' => $id]);
       */
    }

    public function show($id){

        $sql = 'SELECT * FROM albums WHERE id = :id';
        return DB::select($sql, ['id' => $id]);
        //return redirect()->back();
        //dd($id);
    }

    public function edit($id){

        /*
         *   ELOQUENT
         */
        $album = Album::find($id);
        $categories = Category::get();
        $cats_selected = $album->categories->pluck('id')->toArray();


        /* Controllo autorizzazione sulla rotta, senza funzionalità laravel.

        if ($album->user_id !== Auth::user()->id){
            abort(401, 'Non autorizzato');
        }
        */


        /* Controllo con Gate di laravel

        if (Gate::denies('gestione-album', $album)){
            abort(401, 'Non autorizzato');
        }
        */



        // Controllo con Policy di laravel
        $this->authorize('update', $album);

        $queryBuilder = Album::orderBy('id', 'desc')->withCount('photos')
            ->with('categories')->where('user_id', Auth::user()->id);;
        $numAlbums = $queryBuilder->count();
        $ultimoAlbum = $queryBuilder->first();

        if (\request()->has('adminpanel')) {
            return view('admin.adminalbum_edit')->with(['album' => $album,
                'categories' => $categories,
                'cats_selected' => $cats_selected]);
        }

        return view('albums.editalbum')-> with(
            [
                'album' => $album,
                'categories' => $categories,
                'cats_selected' => $cats_selected,
                'lastAlbum' => $ultimoAlbum,
                'numeroAlbums' => $numAlbums,
                'user' => Auth::user()
            ]);

        /*
         *   QUERYBUILDER
         *
        $sql = 'select id, album_name, description from albums where id = :id';
        $album = DB::select($sql, ['id' => $id]);

        return view('albums.editalbum')-> with('album', $album[0]);
        */
    }

    public function store($id, AlbumEditRequest $req){


        /*
         *    ELOQUENT
         */
        $album = Album::find($id);

        // $this->authorize('update', $album); //qui non serve perché il controllo l'abbiamo già in AlbumEditRequest.
        /*
        if (Gate::denies('gestione-album', $album)){
            abort(401, 'Non autorizzato');
        }
        */
        $album->album_name = \request()->input('name');
        $album->description = \request()->input('description');
        //$album->user_id = $req->user()->id;
        $this->thumbsProcessing($id, $req, $album);
        $res = $album->save();
        $album->categories()->sync($req->categories);

        $messaggio = $res ? 'Album '.$id.' aggiornato' : 'Album '.$id.' non aggiornato';
        session()->flash('message', $messaggio);

        if ($req->has('adminpanel')) {
            return redirect()->route('albums.list');
        }
        return redirect()->route('albums');

        /*
         *    ELOQUENT tramite querybuilder
         *
        $res = Album::where('id', $id)->update(
            [
                'album_name' => \request()->input('name'),
                'description' => \request()->input('description')
            ]);
        $messaggio = $res ? 'Album '.$id.' aggiornato' : 'Album '.$id.' non aggiornato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
        */

        /*
         *    METODO CON QUERYBUILDER
         *
        $res = DB::table('albums')->where('id', $id)->update(
            [
                'album_name' => \request()->input('name'),
                'description' => \request()->input('description')
            ]);
        $messaggio = $res ? 'Album '.$id.' aggiornato' : 'Album '.$id.' non aggiornato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
        */

        /*
         *    METODO QUERY GREZZE
         *
        $data = \request()->only(['name', 'description']);
        $data['id'] = $id;
        $sql = 'update albums set album_name = :name, description = :description';
        $sql .= ' where id = :id';
        $res = DB::update($sql, $data);

        $messaggio = $res ? 'Album '.$id.' aggiornato' : 'Album '.$id.' non aggiornato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
        */
    }

    public function create(){
        $album = new Album();
        $categories = Category::get();
        $cats_selected = [];

        $queryBuilder = Album::orderBy('id', 'desc')->withCount('photos')
            ->with('categories')->where('user_id', Auth::user()->id);;
        $numAlbums = $queryBuilder->count();
        if ($numAlbums > 0){
            $ultimoAlbum = $queryBuilder->first();
            return view('albums.createalbum', [
                'album' => $album,
                'categories' => $categories,
                'cats_selected' => $cats_selected,
                'lastAlbum' => $ultimoAlbum,
                'numeroAlbums' =>$numAlbums,
                'user' => Auth::user()
            ]);
        }
        else {
            return view('albums.createalbum', [
                'album' => $album,
                'categories' => $categories,
                'cats_selected' => $cats_selected,
                'lastAlbum' => null,
                'numeroAlbums' =>$numAlbums,
                'user' => Auth::user()
            ]);
        }
    }

    public function save(AlbumRequest $request){

        /*
         *    ELOQUENT
         */
        $album = new Album();
        $album->album_name = $request->input('name');
        $album->album_thumbnail = '';
        $album->album_thumbnail2 = '';
        $album->description = $request->input('description');
        $album->user_id = $request->user()->id;

        $res = $album->save();

        if ($res) {
            if ($request->has('categories')){
                $album->categories()->attach($request->input('categories'));  //con attach andiamo a sfruttare la connessione many to many
                                                                                   // per inserire le categorie  selezionate nella tabella pivot album_category
            }
            if ($this->thumbsProcessing($album->id, $request,$album)){
                $album->save();
            }
        }
        $name = $request->input('name');
        $messaggio = $res ? 'Album '.$name.' creato' : 'Album non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');


        /*
         *    ELOQUENT tramite querybuilder
         *
        $res = Album::insert(          //oppure Album::create() ma in questo caso i campi devono essere scrivili, ovvero specificati nell'attributo $fillable del model.
            [
                'album_name' => \request()->input('name'),
                'description' => \request()->input('description'),
                'user_id' => 7
            ]);
        $name = \request()->input('name');
        $messaggio = $res ? 'Album '.$name.' creato' : 'Album non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
        */

        /*
         *    METODO CON QUERYBUILDER
         *
        $res = DB::table('albums')->insert(
            [
                'album_name' => \request()->input('name'),
                'description' => \request()->input('description'),
                'user_id' => 7
            ]);
        $name = \request()->input('name');
        $messaggio = $res ? 'Album '.$name.' creato' : 'Album non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
        */

        /*
         *   METODO QUERY GREZZE
         *
        $data = \request()->only(['name', 'description']);
        $data['user_id'] = 7;
        $sql = 'insert into albums (album_name, description, user_id)';
        $sql .= ' values (:name, :description, :user_id)';
        $res = DB::insert($sql, $data);
        $messaggio = $res ? 'Album '.$data['name'].' creato' : 'Album non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
        */
    }

    public function showImages(Album $id){
        $albums = Auth::user()->albums()->orderBy('id', 'desc')->withCount('photos')->with('categories');
        //$queryBuilder = Album::orderBy('id', 'desc')->withCount('photos')
          //  ->with('categories')->where('user_id', Auth::user()->id);
        $numAlbums = $albums->count();


        //$images = Photo::where('album_id', $id->id)->orderBy('id', 'desc ')->paginate(15);
        $images = $id->photos()->orderBy('id', 'desc')->paginate(15);

        if ($numAlbums > 0) {
            $ultimoAlbum = $albums->first();
            return view('immagini.immagini_album')->with([
                'id' => $id,
                'images' => $images,
                'lastAlbum' => $ultimoAlbum,
                'numeroAlbums' => $numAlbums,
                'user' => Auth::user()
            ]);
        }else{
            return view('immagini.immagini_album')->with([
                'id' => $id,
                'images' => $images,
                'lastAlbum' => null,
                'numeroAlbums' => $numAlbums,
                'user' => Auth::user()
            ]);
        }
    }

    /**
     * @param $id
     * @param Request $req
     * @param $album
     */
    public function thumbsProcessing($id, Request $req, &$album)
    {
        if (!$req->hasFile('album_thumbnail') || !$req->file('album_thumbnail')->isValid()){
            return false;
        }
        $file = $req->file('album_thumbnail');
        $img = Image::make($file->getRealPath())->resize(120, null, function ($constraint){
             $constraint->aspectRatio();
         });

        $img2 = Image::make($file->getRealPath())->resize(300, null, function ($constraint){
            $constraint->aspectRatio();
        });
                $fileName = $id . '.' . $file->extension();
                $fileName2 = $id . '_2.' . $file->extension();
                $img->save(storage_path('app/public/'.env('ALBUM_THUMBS_DIR').'/'.$fileName),80);
                $img2->save(storage_path('app/public/'.env('ALBUM_THUMBS_DIR').'/'.$fileName2),80);
                //$file->storeAs(env('ALBUM_THUMBS_DIR'), $fileName, 'public');
                $album->album_thumbnail = env('ALBUM_THUMBS_DIR') . '/' . $fileName;
                $album->album_thumbnail2 = env('ALBUM_THUMBS_DIR') . '/' . $fileName2;
                return true;
    }



    public function getAll(){
        $albums = Album::has('user')->orderBy('id', 'desc')->withCount('photos')->with('user')->get();
        return view('admin.adminalbums')->with('albums', $albums);
    }




}
