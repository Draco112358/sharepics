<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Actuallymab\LaravelComment\Commentable;

class Photo extends Model
{
    use Commentable;
    protected $canBeRated = true;
    protected $mustBeApproved = false;

    protected $fillable = ['name', 'img_path', 'description', 'img_thumbnail'];

    public function getPathAttribute(){
        $url = $this->attributes['img_path'];
        if (stristr($url, 'http') != true){
            $url = 'storage/'.$url;
        }

        return $url;
    }

    public function getPathumbAttribute(){
        $url = $this->img_thumbnail;
        if (stristr($this->img_thumbnail, 'http') != true){
            $url = 'storage/'.$this->img_thumbnail;
        }

        return $url;
    }

    /*
    public function getImgPathAttribute($val){

        if (stristr($val, 'http') != true){
            $val = 'storage/'.$val;
        }

        return $val;
    }*/

    public function album(){
       // $this>$this->belongsTo(Album::class, 'album_id', 'id');
        return $this->belongsTo(Album::class); //non serve indicare le chiave se rispettano le convenzioni sui nomi
                                                      // perché laravel le riconosce in automatico.
    }

    public function getUserAttribute(){
        return $this->album->user;
    }


    public function averageRate()
    {
        return ($this->getCanBeRated()) ? $this->comments()->where('approved', true)->where('rate', '!=', 0)->avg('rate') : 0;
    }
}

