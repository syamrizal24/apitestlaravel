<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;
    protected $table = "pajaks";
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'rate'];

    public function item()
    {
        return $this->belongsToMany('App\Models\Item');
    }
}
