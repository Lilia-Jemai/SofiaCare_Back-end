<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medic extends Model
{
    use HasFactory;

    protected $fillable = [
        
        //'fcmtoken',
        'num_tel',
        'adresse',
        //'specialite',
        'user_id',
        'spec_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function spec(){
        return $this->belongsTo(Specialite::class);
    }

}
