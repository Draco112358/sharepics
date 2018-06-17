<?php
namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Album extends Model{


    //  protected $table = 'nome_tabella';
    //  protected $primaryKey = 'campo_primary_key';


    /*
     *  La proprietà $fillable serve a specificare i campi scrivibili della tabella, ad esempio se
     * vogliamo creare un record con il metodo create anziché con l'insert
     */

    protected $fillable = ['album_name', 'album_thumbnail','album_thumbnail2', 'description', 'user_id'];


    /*
     * Con la convenzione getNameAttribute, dove Name è il nome di una proprietà che vogliamo usare come attributo,
     * potremo richiamare questo metodo come un attributo $album->name.
     * Nel caso specifico avendo inserito getPathAttribute potremo invocare $album->path dove $album
     * è un'istanza del model Album.
     */
    public function getPathAttribute(){
        $url = $this->album_thumbnail;
        if (stristr($this->album_thumbnail, 'http') != true){
            $url = 'storage/'.$this->album_thumbnail;
        }

        return $url;
    }

    public function getPath2Attribute(){
        $url = $this->album_thumbnail2;
        if (stristr($this->album_thumbnail2, 'http') != true){
            $url = 'storage/'.$this->album_thumbnail2;
        }

        return $url;
    }

    // RELAZIONI
    public function photos(){

        return $this->hasMany(Photo::class, 'album_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'album_category')->withTimestamps();
    }
}