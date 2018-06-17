<?php

namespace App\Http\Controllers;

use Actuallymab\LaravelComment\Models\Comment;
use App\Http\Requests\CommentInsertRequest;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Photo $photo  l'id della photo per il model binding
     * @param Request|null $request
     * @return \Illuminate\Http\Response
     */
    public function index(Photo $comment, Request $request)
    {

        $comments = $comment->comments()->latest('id')->with('commented')->get();
        $comments = $comments->filter(function ($comm){
            return $comm->commented != null;
        })->paginate(10);

        $commentsCount = $comment->totalCommentCount();
        $mediaVoti = round($comment->averageRate(),1);
        return view('commenti.commenti_immagine')
            ->with([
                'immagine' => $comment,
                'commenti' => $comments,
                'numeroCommenti' => $commentsCount,
                'mediaVoti' => $mediaVoti
            ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentInsertRequest $request)
    {
        $user = Auth::user();
        $photo = Photo::find($request->input('photoID'));
        if ($request->has('rating')) {
            $user->comment($photo, $request->input('nuovoCommento'), $request->input('rating'));
        }else{
            $user->comment($photo, $request->input('nuovoCommento'));
        }
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $comment
     * @return \Illuminate\Http\Response|string
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        if (!Auth::user()->isAdmin()){
            abort(401, 'Non autorizzato');
        }

        $res = $comment->delete();
        return ''.$res;
    }

    public function getAll(){
        $commenti = Comment::with('commented')->with('commentable')->orderBy('commentable_id')->get();
        $commenti = $commenti->filter(function ($commento){
            return $commento->commented != null;
        });


        return view('admin.admincomments')->with('commenti', $commenti);
    }


}
