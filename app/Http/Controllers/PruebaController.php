<?php
namespace App\Http\Controllers;

use App\BusinessLayer\Prueba;
/**
 * 
 */
class PruebaController
{
	private $pruebaBll;
	private $respuesta;

	function __construct()
	{
		$this->pruebaBll = new Prueba();
	}
	public function index()
	{
		try {
			$this->respuesta = $this->pruebaBll->index();
		} catch (\Exception $e) {
			throw new \Exception("Error Processing Request", 1);
					
		}finally{
			return response()->json($this->respuesta);
		}
		// return response()->json('Hola Mundo');
	}
}