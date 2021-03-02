<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = "items";
    protected $primaryKey = 'id';
    protected $fillable = ['nama'];

    public function pajak()
    {
        return $this->belongsToMany('App\Models\Pajak');
    }
}
