<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medic extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'patient',
        'experience',
        'bio_data',
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