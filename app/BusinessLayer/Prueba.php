<?php

namespace App\BusinessLayer;

use App\Repositories\PruebaRepository;

/**
 * 
 */
class Prueba
{
	
	public function index()
	{
		try {
			$pruebaRepo =  new PruebaRepository();
			$respuesta = $pruebaRepo->listar();
			return $respuesta;

		} catch (\Exception $e) {
			throw new \Exception("Error Processing Request", 1);		
		}
	}
}