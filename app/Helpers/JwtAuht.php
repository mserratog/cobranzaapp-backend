<?php
namespace App\Helpers;

//require_once 'vendor\firebase\php\src\JWT.php';

//require_once "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use PhpParser\Node\Stmt\Else_;

class JwtAuth{

    public $key;
    public function __construct()
    {
        $this->key = 'esto_es_una_clave_super_secreta_1234';
    }

    public function signup($email,$password,$getToken = null){
        // BUSCAR SI EXISTE EL USUARIO CON SUS CREDENCIALES
        $user = Usuario::where([
                'email' =>$email,
                'password' => $password
        ])->first();


        // COMPROBAR SI SON CORRECTAS(OBJETO)
        $signup=false;
        if(is_object($user)){
            $signup=true;
        }

        // GENERAR EL TOKEN CON LOS DATOS DEL USUARIO IDENTIFICADO
        if($signup){

            $token = array(
                'sub'   =>  $user->id,
                'email'   =>  $user->email,
                'name'   =>  $user->nombre,
                'surname'   =>  $user->apellidos,
                'iat'   =>  time(),
                'exp'   =>  time()+(7*24*60*60),

            );


            $clave = 'esto_es_una_clave_super_secreta_1234';
            $jwt =  JWT::encode($token,$clave,'HS256');
            $decoded = JWT::decode($jwt, new Key($clave, 'HS256'));

            // DEVOLVER LOS DATOS DECODIGICADOS O EL TOKEN, EM FUNCION DE UN PARAMETRO
            
            if(is_null($getToken)) {
                $data= $jwt;
            }
            else{
                $data= $decoded;
            }
        }
        else{
            $data = array(
                'status'=>'Error',
                'mesage'=>'Error en login token'
            );
        }


        return $data;

    }

    public function checkToken($jwt, $getIdentity=false){
        $auth=false;
        try {
            $clave = 'esto_es_una_clave_super_secreta_1234';
            $jwt= str_replace('"','',$jwt);
            $decoded = JWT::decode($jwt, new Key($clave, 'HS256'));
        } catch (\UnexpectedValueException $th) {
            $auth=false;
        }catch(\DomainException $e){
            $auth=false;


        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth=true;
        }
        else{
            $auth=false;
        }

        if($getIdentity){
            return $decoded;

        }
        return $auth;
    }

}
