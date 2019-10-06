<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class registros extends Eloquent
{
	protected $connection = 'mongodb';

	protected $table = 'registros';
    //
    protected $fillable = [
    	'_id', 'direccion', 'departamento', 'municipio', 'foto', 'posision_mapa_top', 'posision_mapa_left', 'registros',
    ];
}
