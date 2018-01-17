<?php
use Firebase\JWT\JWT;
class Controller_usuario extends Controller_Rest
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create()
    {
        $input = $_POST;
        if (array_key_exists('username', $input)&& array_key_exists('email', $input) && array_key_exists('passwordRepeat', $input) && array_key_exists('password', $input) && array_key_exists('rol', $input) && array_key_exists('x', $input) && array_key_exists('y', $input) && array_key_exists('id_device', $input)){
            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('username', $input['username'])
                ),
            ));
            $BDemail = Model_Usuarios::find('first', array(
                'where' => array(
                    array('email', $input['email'])
                ),
            ));
            if ($input['password'] == $input['passwordRepeat']){
                if(count($BDemail) < 1){
                    if(count($BDuser) < 1){
                        $new = new Model_Usuarios();
                        $new->username = $input['username'];
                        $new->email = $input['email'];
                        $new->password = $input['password'];
                        $new->id_rol = $input['rol'];
                        $new->x = $input['x'];
                        $new->y = $input['y'];
                        $new->id_device = $input['id_device'];
                        $new->profile_photo = $input['profile_photo'];
                        $new->birthday = $input['birthday'];
                        $new->city = $input['city'];
                        $new->description = $input['description'];
                        $new->id_privacity = $input['id_privacity'];
                        $new->save();

                        $this->Mensaje('200', 'usuario creado', $input);
                    } else {
                        $this->Mensaje('400', 'usuario ya existe', $input['username']);
                    }
                } else {
                    $this->Mensaje('400', 'email ya esta en uso', $input['email']);
                }
            }else {
                $this->Mensaje('400', 'contraseñas no coinciden', $input['password']);
            }
        } else{
            $this->Mensaje('400', 'Parametros invalidos', $input);
        }    
    }

    public function get_login()
    {
        $username = $_GET['username'];
        $password = $_GET['password'];
        if(!empty($username) && !empty($password)){
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

             $this->Mensaje('200', 'usuario logueado', $jwt);
         } else {
            $this->Mensaje('400', 'usuario invalido', $username);
        }
    }else {
        $this->Mensaje('400', 'parametros vacios', $username);
    }
}

public function post_loginAPP()
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(!empty($username) && !empty($password)){
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
                'password'=> $password,
                'x'=>$BDuser->x,
                'y'=>$BDuser->y,
            ]
        );

         $jwt = JWT::encode($token, $this->key);

         $this->Mensaje('200', 'usuario logueado', $jwt);
     } else {
        $this->Mensaje('400', 'usuario invalido', $username);
    }
}else {
    $this->Mensaje('400', 'parametros vacios', $username);
}
}


public function get_Users(){
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
        $this->Mensaje('200', 'lista de usuarios', $users);
    }else {
        $this->Mensaje('400', 'usuario invalido', $username);
    }
}

public function post_modify(){

    $jwt = apache_request_headers()['Authorization'];

    try{
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
            $this->Mensaje('200', 'usuario modificado', $input['password']);
        } else {
            $this->Mensaje('400', 'usuario invalido', $input['username']);
        }
    } catch(Exception $e) {
        $this->Mensaje('400', 'Error de verificacion', "aprender a programar");
    } 
}

public function post_deleteUser(){
    $jwt = apache_request_headers()['Authorization'];

    if(!empty($jwt)){
        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('first', array(
            'where' => array(
                array('id', $id)
            ),
        ));
        if($BDuser != null){

            $BDuser->delete();

            $this->Mensaje('200', 'usuario borrado', $BDuser);
        } else {
            $this->Mensaje('400', 'usuario invalido', $input['username']);
        }
    } else {
        $this->Mensaje('400', 'token vacio', $jwt);
    }
}

function get_getNearUsers(){
    $jwt = apache_request_headers()['Authorization'];

    $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

    $id = $tokenDecode->data->id;
    $x = $tokenDecode->data->x;
    $y = $tokenDecode->data->y;

    $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
        ),
    ));

    if(count($BDuser) == 1){
        Model_Usuarios::find('all', array(
            'where' => array(
                array('x', $x),
                array('y', $y)
            ),
        ));
        $this->Mensaje('200', 'lista de usuarios cercanos', $users);
    }else {
        $this->Mensaje('400', 'usuario invalido', $username);
    }
}

public function get_recoverPassword(){
    $email = $_GET['email'];
    try{
        $BDuser = Model_Usuarios::find('first', array(
            'where' => array(
                array('email', $email)
            ),
        ));
        if($BDuser != null){



            $this->Mensaje('200', 'email correcto', $BDuser);
        } else {
            $this->Mensaje('400', 'email invalido', $email);
        }

    }catch(Exception $e) {
        $this->Mensaje('500', 'Error de servidor', "aprender a programar");
    }
}

function post_configAdmin(){
    $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('username', 'admin')
        ),
    ));
    $BDemail = Model_Usuarios::find('first', array(
        'where' => array(
            array('email', 'admin@admin.es')
        ),
    ));
    if(count($BDemail) < 1){
        if(count($BDuser) < 1){
            $new = new Model_Usuarios();
            $new->username = 'admin';
            $new->email = 'admin@admin.es';
            $new->password = '1234';
            $new->id_rol = '1';
            $new->x = '0';
            $new->y = '0';
            $new->id_device = '0';
            $new->profile_photo = 'foto';
            $new->birthday = '01/01/1990';
            $new->city = 'madrid';
            $new->description = 'admin';
            $new->id_privacity = '1';
            $new->save();

            $this->Mensaje('200', 'usuario admin creado', 'admin');
        } else {
            $this->Mensaje('400', 'usuario ya existe', $input['username']);
        }
    } else {
        $this->Mensaje('400', 'email ya esta en uso', $input['email']);
    }     
}

function Mensaje($code, $message, $data){
    $json = $this->response(array(
        'code' => $code,
        'message' => $message,
        'data' => $data
    ));
    return $json;
}
}