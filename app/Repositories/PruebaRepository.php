<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
/**
 * 
 */
class PruebaRepository 
{	
	public function listar()
	{
		try {
			// $array = DB::select('select now()');
			$array = DB::select("select now()");
			//$array = DB::table('prueba')->get();
		} catch (\Exception $e) {
			throw new \Exception("Error Processing Request", 1);
		}
		return $array;
	}
}