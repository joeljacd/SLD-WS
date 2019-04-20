<?php

namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\LoginRepository;

class Login
{
	public function generaAcciones($codEmpresa, $codPerfil)
    {
        try {
	        $et = new EstadoTransaccion();
	        $loginRepo = new LoginRepository();

	        $r = $loginRepo->consultaAcciones($codEmpresa, $codPerfil);
	        if ($r[0]->valido == 0) {
	        	$et->existeError = true;
	        	$et->mensaje = [
	        		'user' => 'Error al cargar las acciones'
	        	];
	        } else {
	            $acciones =  collect($r);

	            $acciones = $acciones->groupBy('Nombre_modulo');
	            
	            $acciones = $acciones->map(function($item, $key) {
	                return $item->groupBy('Nombre_opcion');
	            });

	            $acciones = $acciones->map(function($item, $key) {
	                return $item->map(function($item, $key) {
	                    $nombreAccion = ($item->implode('Nombre_accion', ','));
	                    return $item->map(function($item, $key) use($nombreAccion) {
	                        $item->{'Nombre_accion'} = $nombreAccion;
	                        $item = collect($item)->forget(['Nombre_modulo', 'Nombre_opcion', 'valido']); //Eliminar de cada subnodo final estas keys
	                        return $item;
	                    });
	                });
	            });
	            $et->data = $acciones->map(function($item, $key) {
	                return $item->map(function($item, $key) {
	                    return ($item->unique());
	                });
	            });
	        }
        } catch (\Exception $e) {
        	throw new \Exception(' : ' . className($this) . '->generaAcciones : ' . $e->getMessage());
        }
        return $et;
    }

	public function generaMenu($codEmpresa, $codUsuario, $codPerfil)
    {
        try {
        	$et = new EstadoTransaccion();
			$loginRepo = new LoginRepository();

			$r = $loginRepo->consultaMenu($codEmpresa, $codUsuario, $codPerfil);
		    if($r[0]->valido == 0) {
	        	$et->existeError = true;
	        	$et->mensaje = [
	        		'user' => 'Error al cargar el menú'
	        	];
	        } else {
	        	$menu = collect($r);
	        	$menu = $menu->groupBy('Nombre_modulo');
		        $menu = $menu->map(function($item, $key){
		            return $item->groupBy('Agrupacion_logica');
		        });
		        $et->data = $menu->map(function($item, $key){
		            return $item->map(function($item, $key){
		                return $item->map(function($item, $key){
		                    return collect($item)->forget(['Nombre_modulo', 'Agrupacion_logica']); //Eliminar de cada subnodo final estas keys
		                });
		            });
		        });
	        	
	        }
        } catch (\Exception $e) {
        	throw new \Exception(' : ' . className($this) . '->generaMenu : ' . $e->getMessage());
        }
	    return $et;
    }

    public function userInfo($usrName, $usrPass, $codEmpresa)
	{
		try {
			$et = new EstadoTransaccion();
			$loginRepo = new LoginRepository();
			$r = $loginRepo->login($usrName, $usrPass, $codEmpresa);
	        // if($r[0]->_usuario == 1) {
			if(count($r) > 0){
	            $et->data = $r[0];
	        } else {
	            $et->existeError = true;
	            $et->mensaje = [
	            	'user' => 'Credenciales inválidas',
	            ];
	        }
		} catch (Exception $e) {
			throw new \Exception(' : ' . className($this) . '->userInfo : ' . $e->getMessage());
		}
		return $et;
	}
}