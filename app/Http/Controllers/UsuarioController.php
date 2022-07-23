<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    //
    public function getUsuarios(Request $request){

        $nombre = $request->input('name');
        return 'Api getUsuarios'.$nombre;

    }



    public function register(Request $request){

        /*
        $data = array(
            'status'  => 'error',
            'code'    => '404',
            'message' => 'EL usuario no se ha registrado'
        );

        return response()->json($data,$data['code']);
        */

        //Recoger datos de Json
        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        //Limpiar datos , quitar espacios
        $params_array = array_map('trim',$params_array);

        //Validar datos
        $validate = Validator::make($params_array,
        [
            'name' => 'required|alpha',
            'surname' => 'required',
            'email' => 'required|email|unique:usuario',
            'password' => 'required'
        ]
    );

    if($validate->fails()){
        $data = array(
            'status'  => 'error',
            'code'    => '404',
            'message' => 'EL usuario no se ha registrado',
            'errors'  => $validate->errors()
        );

    }else{
        //Cifrar contraseña 4 veces
        //$pwd = password_hash($params->password,PASSWORD_BCRYPT,['cost'=>4]);
        $pwd = hash('sha256',$params->password);

        $usuario= new Usuario();
        $usuario->nombre=$params_array['name'];
        $usuario->apellidos=$params_array['surname'];
        $usuario->email=$params_array['email'];
        $usuario->password=$pwd;

        //Guardar en BD
        $sql_code=$usuario->save();

        if($sql_code){
            $data = array(
                'status'  => 'succes',
                'code'    => '200',
                'message' => 'EL usuario se ha registrado correctamente',
                'user'    => $usuario
            );


        }
        else{

            $data = array(
                'status'  => 'error',
                'code'    => '400',
                'message' => 'Error al grabar usuario'
            );

        }




    }

    return response()->json($data,$data['code']);

    }

    /*
    public function login(Request $request){

        $jwtAut = new \App\Helpers\JwtAuth;


        $email="angelserratog@gmail.com";
        $password="123456";

        return response()->json($jwtAut->signup($email,$password,true),200);




    }*/

    public function login(Request $request){

        //$jwtAuth = new \App\Helpers\JwtAuth;

        //recibir datos del post
        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        //validar
        $validate = Validator::make($params_array,
        [
            'email' => 'required|email',
            'password' => 'required'
        ]
            );

            if($validate->fails()){
                $data = array(
                    'status'  => 'error',
                    'code'    => '404',
                    'message' => 'EL usuario no se ha podido identificar',
                    'errors'  => $validate->errors()
                );


            }else{
                $email=$params->email;


                //$signup = $jwtAuth->signup($params->email,$pwd);
                $signup='IiOjgsImVtYWlsIjoibXNlcnJhdG9AZ21haWwuY29tIiwibmFtZSI6Ik1JR1VFT';
                if(!empty($params->gettoken)){
                  //  $signup = $jwtAuth->signup($params->email,$pwd,true);
                  $pwd= hash('sha256',$params->password);
                  $user = Usuario::where([
                    'email' =>$email,
                    'password' => $pwd
                ])->first();

                  $signup = array(
                    'sub'   =>  $user->id,
                    'email'   =>  $user->email,
                    'name'   =>  $user->nombre,
                    'surname'   =>  $user->apellidos,
                    'iat'   =>  time(),
                    'exp'   =>  time()+(7*24*60*60),

                );
                }


            }



            return response()->json($signup,200);
    }

    public function update(Request $request){

        //comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        //$jwtAuth = new \App\Helpers\JwtAuth;

        //$checkToken = $jwtAuth->checkToken($token);
        $$checkToken=true;
        //Recoger los datos por post
        $json = $request->input('json',null);
        $params_array =json_decode($json,true);//array


        if($checkToken && !empty($params_array)){


            //Obtener el usuario identificado
            //$user = $jwtAuth->checkToken($token,true);

            //Validar datos
            $validate = Validator::make($params_array,
        [
            'nombre' => 'required|alpha',
            'apellidos' => 'required|alpha',
            'email' => 'required|email|unique:usuario'//.$user->sub
        ]
            );

            //Eliminar los campos que no quiero actualizar

            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //actualizar usuario en BASE DE DATOS
            $user_update = Usuario::where('id', $user->sub)->update($params_array);

            //devolver array con resultado
            $data = array(
                'status'  => 'success',
                'code'    => '200',
                'message' => 'Usuario actualizado',
                'user'    => $user,
                'changes' => $params_array
            );


        }
        else{
            $data = array(
                'status'  => 'error',
                'code'    => '404',
                'message' => 'EL usuario no esta autenticado'
            );
        }

        return response()->json($data,$data['code']);
    }


    public function detail($id){

        $user= Usuario::find($id);

        if(is_object($user)){

            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'user' => $user
            );

        }else{
            $data = array(
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Usuario no existe o esta inactivo'
            );

        }
        return response()->json($data,$data['code']);

    }

}
