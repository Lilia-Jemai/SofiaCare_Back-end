<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'med_id',
    ];

    public function medic(){
        return $this->belongsTo(Medic::class);
    }
}
