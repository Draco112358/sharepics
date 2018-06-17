<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];








    /**Relazione molti a molti con la tabella albums,
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function albums(){
        return $this->belongsToMany(Album::class, 'album_category', 'category_id', 'album_id')
            ->withTimestamps();
    }
}
