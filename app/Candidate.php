<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates';

    public $timestamps = true;

    protected $fillable = [
        'cedula',
        'firstname',
        'lastname',
        'position',
        'photo',
        'photo_path',
        'logo',
        'logo_path',
    ];


}
