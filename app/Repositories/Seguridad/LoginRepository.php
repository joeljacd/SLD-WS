<?php

namespace App\Repositories\Seguridad;

use Illuminate\Support\Facades\DB;

class LoginRepository
{
	public function consultaAcciones($codEmpresa, $codPerfil)
	{
		try {
			$r = DB::select('CALL ACA_SEG_ListarAcciones(?, ?)', [
				$codEmpresa,
				$codPerfil
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . className($this) . '->consultaAcciones : ' . $e->getMessage());
		}
		return $r;
	}

	public function consultaMenu($codEmpresa, $codUsuario, $codPerfil)
	{

		try {
			$r = DB::select('CALL ACA_SEG_Menu(?,?,?)', [
				$codEmpresa,
				$codUsuario,
				$codPerfil
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . className($this) . '->consultaMenu : ' . $e->getMessage());
		}
		return $r;
	}

	public function login($usrName, $usrPass, $codEmpresa)
	{
		try {
			$r = DB::select('CALL ACA_SEG_Login(?,?,?)', [
				$usrName,
				$usrPass,
				$codEmpresa
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . className($this) . '->login : ' . $e->getMessage());
		}
		return $r;
	}
}