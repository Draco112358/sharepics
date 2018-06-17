<?php

namespace App\Http\Controllers\Admin;

use Actuallymab\LaravelComment\Models\Comment;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\User;
use App\Http\Controllers\Controller;
use function Composer\Autoload\includeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if (!$request->has('trashed')) {
            $users = User::orderBy('name')->withTrashed()->get();
            $trashed = null;
        }
        else{
            $users = User::orderBy('name')->onlyTrashed()->get();
            $trashed = 1;
        }
        return view('admin/adminusers')
            ->with([
                'users' => $users,
                'disattivati' => $trashed
            ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.adminuser_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role = $request->input('role');
        $user->setRememberToken(str_random(10));

        $res = $user->save();
        $messaggio = $res ? 'Utente '.$user->name.' creato' : 'Utente non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('users.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::withTrashed()->find($id);
        $albums = $user->albums;
        dd($albums);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.adminuser_edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserEditRequest $request)
    {

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        $res = $user->save();
        $messaggio = $res ? 'Utente '.$user->name.' aggiornato' : 'Utente non aggiornato';
        session()->flash('message', $messaggio);
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response|string
     */
    public function destroy($id, Request $request)
    {
        $user = User::withTrashed()->find($id);
        if ($user->trashed()){
            $user->restore();
        }
        if ($request->input('hard')){
            $albums = $user->albums;
            $photos = $user->photos;

            // Eliminiamo i commenti relativi alle foto dell'utente da eliminare.
            if ($photos != null) {
                foreach ($photos as $photo) {
                    $photo->comments()->delete();
                }
            }

            //Eliminiamo i commenti inseriti dall'utente da eliminare.
            $comments = Comment::with('commented')->get();
            if ($comments != null){
                foreach ($comments as $comment){
                    if ($comment->commented->email == $user->email){
                        $comment->delete();
                    }
                }
            }


            //Eliminiamo l'utente.
            $res = $user->forceDelete();

            //Eliminiamo i files dell'utente.
            if ($res){
                if ($albums != null) {
                    foreach ($albums as $album) {
                        $album_images = env('IMG_DIR') . '/' . $album->id;
                        $images_thumbs = env('IMG_THUMBS_DIR') . '/' . $album->id;
                        if ($album->album_thumbnail && Storage::disk('public')->has($album->album_thumbnail)) {
                            Storage::disk('public')->delete($album->album_thumbnail);
                        }
                        if ($album->album_thumbnail2 && Storage::disk('public')->has($album->album_thumbnail2)) {
                            Storage::disk('public')->delete($album->album_thumbnail2);
                        }
                        if (\File::isDirectory(Storage::disk('public')->path($album_images))) {
                           \File::deleteDirectory(Storage::disk('public')->path($album_images));

                        }
                        if (\File::isDirectory(Storage::disk('public')->path($images_thumbs))) {
                            \File::deleteDirectory(Storage::disk('public')->path($images_thumbs));

                        }
                    }
                }
            }
        }
        else{
            $res = $user->delete();
        }

        return ''.$res;
    }

    public function restore($id){
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect()->route('users.index');
    }
}
