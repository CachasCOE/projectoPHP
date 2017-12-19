<?php
use Firebase\JWT\JWT;
class Controller_usuario extends Controller_Rest
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create()
    {
        $input = $_POST;
        $new = new Model_Usuarios();
        $new->username = $input['username'];
        $new->email = $input['email'];
        $new->password = $input['password'];
        $new->id_rol = $input['rol'];
        $new->save();

        return $this->response(array(
            'response' => 200,
            'message' => 'usuario creado',
            'username' => $input['username'],
            'password' => $input['password'],
            'rol' => $input['rol']
        ));
    }

    public function get_login()
    {
    	$username = $_GET['username'];
    	$password = $_GET['password'];

        $BDuser = Model_Usuarios::find('first', array(
         'where' => array(
             array('username', $username),
             array('password', $password)
         ),
     ));

        if(count($BDuser) == 1){
        	$time = time();
            $token = array(
                'iat' => $time,
	    		'data' => [ // información del usuario
                'id' => $BDuser->id,
                'username' => $username,
                'password'=> $password
            ]
        );

            $jwt = JWT::encode($token, $this->key);

            $json = $this->response(array(
                'response' => 200,
                'message' => 'usuario hallado',
                'username' => $username,
                'password' => $password,
                'token' => $jwt
            ));
        } else {
            $json = $this->response(array(
                'response' => 400,
                'message' => 'usuario invalido'
            ));
        }
    }

    public function get_authorization(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

        $username = $tokenDecode->data->username;
        $password = $tokenDecode->data->password;

        $BDuser = Model_Usuarios::find('all', array(
            'where' => array(
                array('username', $username),
                array('password', $password)
            ),
        ));

        if(count($BDuser) == 1){
            $users = Model_Usuarios::find('all');
            $json = $this->response(array(
                'code' => 200,
                'message' => 'lista usuarios',
                'data' => $users
            ));
        }else {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Invalid user',
                'data' => 'empty'
            ));
        }
        return $json;
    }

    public function post_modify(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

        $username = $tokenDecode->data->username;
        $password = $tokenDecode->data->password;

        $input = $_POST;

        $BDuser = Model_Usuarios::find('first', array(
            'where' => array(
                array('username', $username),
                array('password', $password)
            ),
        ));

        if($BDuser != null){
            $BDuser->password = $input['password'];
            $BDuser->save();
            $json = $this->response(array(
                'code' => 200,
                'message' => 'user modificado',
                'password' => $input['password']
            ));;
        } else {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Invalid user'
            ));
        }
        return $json;
    }

    public function post_deleteUser(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('first', array(
            'where' => array(
                array('id', $id)
            ),
        ));
        if($BDuser != null){

            $BDuser->delete();

            $json = $this->response(array(
                'code' => 200,
                'message' => 'User deleted',
                'data' => $BDuser
            ));;
        } else {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Songs not found'
            ));
        }
    }

}