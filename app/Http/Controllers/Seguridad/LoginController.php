<?php
namespace App\Http\Controllers\Seguridad;

use App\BusinessLayer\Seguridad\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\AuthToken\JWToken;
use Validator;

class LoginController extends Controller{

    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;

    public function __construct(){
        $this->et = new EstadoTransaccion();
        $this->modulo = 'seguridad';
        $this->opcion = 'login';
    }
  
    public function login(Request $request)
    {
        // return $request->all();
        try {
            $login = new Login();
            $objJWToken = new JWToken();
            $request = json_decode($request->getContent(), true);
            
            $this->et = $this->validarData($request);
            
            if($this->et->existeError) {
                throw new \Exception("Error de validación de datos");
            }
            
            $usrName          = $request['usr_name'];
            $usrPass          = $request['usr_pass'];
            $this->codEmpresa = $request['cod_empresa'];

            $usrPass = $this->cifrarPassword($usrName, $usrPass);

            $this->et = $login->userInfo($usrName, $usrPass, $this->codEmpresa);
            // dd($this->et->data->idRol);
            if($this->et->existeError) {
                throw new \Exception('Error controlado. Generado desde un repository o una BLL');
            }

            $userInfo = $this->et->data;

            $this->codUsuario = $userInfo->idRol;
            // $codPerfil = $userInfo->cod_perfil;
            // $codPersona =$userInfo->cod_persona;

            // unset($userInfo->cod_usuario);
            // unset($userInfo->cod_perfil);
            // unset($userInfo->cod_persona);
            // unset($userInfo->_usuario);

            // $this->et = $login->generaMenu($this->codEmpresa, $this->codUsuario, $codPerfil);
                    
            // if($this->et->existeError) {
            //     throw new \Exception('Error controlado. Generado desde un repository o una BLL');
            // }

            // $menu = $this->et->data;

            // $this->et = $login->generaAcciones($this->codEmpresa, $codPerfil);

            if($this->et->existeError) {
                throw new \Exception('Error controlado. Generado desde un repository o una BLL');
            }

            // $acciones = $this->et->data;

            $token = $objJWToken->generarToken($this->codEmpresa, $this->codUsuario);
           
            $this->et->data =  [
                'Token'       => $token,
                'Informacion' => $userInfo,
                'Menu'        => ''//$menu,
                // 'Acciones'    => $acciones,
            ];
        } catch (\Exception $e) {
            $this->et->existeError = true;
            $this->et->data = NULL;
            $this->mensaje = utf8_encode('Error: ' .get_class($this) . '->login : ' .$e->getMessage());
            if( !is_array($this->et->mensaje) ) {
                $this->et->mensaje = [
                    // 'user' => $this->et->mensaje,
                    'user' => EstadoTransaccion::$procesoErroneo
                ];
            }
            // dd($this->et);
        }
        return response()->json($this->et);
    }

    private function cifrarPassword($usrName, $usrPass){
        $cod_clave       = hash('sha256', $usrPass);
        $cod_clave       = substr($cod_clave, 3, 40) . substr($cod_clave, 11, 30);
        $cod_user        = hash('sha256', $usrName);
        $cod_clave_final = $cod_clave . $cod_user;
        $usrPass         = hash('sha256', $cod_clave_final);

        return $usrPass;
    }

    private function validarData($request){
        $this->et = new EstadoTransaccion();
        $reglas = [
            'usr_name'    => 'required|max:255',
            'usr_pass'    => 'required|max:20',
        ];

        $validacion = Validator::make($request, $reglas);

        $errores = '';
        if ($validacion->fails()){
            $this->et->existeError = true;
            foreach ($validacion->messages()->all() as $mensaje) {
                $errores .= $mensaje . '<br/>';
            }
            $this->et->mensaje = [
                'user' => $errores,
            ];
            // $this->et->mensaje = $validacion->messages();
            // $this->et->mensaje = $validacion->messages()->all();
        }
        return $this->et; 
    }
}
