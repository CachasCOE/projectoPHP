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
	        		'password'=> $password,
                    'rol'=> $id_rol
	    		]
			);
        }
        $jwt = JWT::encode($token, $this->key);

        return $this->response(array(
                'response' => 200,
                'message' => 'usuario hallado',
                'username' => $username,
                'password' => $password,
                'token' => $jwt
            ));
    }

    public function get_authorization(){
        $token = $_GET['token'];

        $tokenDecode = JWT::decode($token, $this->key , array('HS256'));
        
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
                    'data' => $users
                ));
        }
            
        return $json;
    }

    public function post_modify(){
        $input = $_POST;

        $token = $input['token'];

        $tokenDecode = JWT::decode($token, $this->key , array('HS256'));
        
        $username = $tokenDecode->data->username;
        $password = $tokenDecode->data->password;

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('username', $username),
            array('password', $password)
            ),
        ));

        if($BDuser != null){
            $BDuser->username = $input['username'];
            $BDuser->password = $input['password'];
            $BDuser->save();
        }
            
        return $this->response(array(
                    'code' => 200,
                    'username' => $input['username'],
                    'password' => $input['password']
                ));;
    }

    public function post_createList()
    {
        $input = $_POST;
        $token = $input['token']
        $title = $input['title'];
        //$id_usuario = $input['id_usuario'];

        $tokenDecode = JWT::decode($token, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;


        $new = new Model_Listas();
        $new->title = $input['title'];
        $new->id_usuario = $id;
        $new->save();

        return $this->response(array(
                'response' => 200,
                'message' => 'lista creada',
                'title' => $input['title'],
            ));
    }

    public function get_lists(){
        $token = $_GET['token'];

        $tokenDecode = JWT::decode($token, $this->key , array('HS256'));
        
        $username = $tokenDecode->data->username;
        $password = $tokenDecode->data->password;
        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('all', array(
        'where' => array(
            array('username', $username),
            array('password', $password)
            ),
        ));

        if(count($BDuser) == 1){
            $lists = Model_Listas::find('all', array(
                'where' => array(
                    array('id_usuario', $id)
                    ),
                ));
            $json = $this->response(array(
                    'code' => 200,
                    'data' => $lists
                ));
        }
            
        return $json;
    }

    public function post_deleteList(){
        $input = $_POST;

        $id_item = $input['id_item'];

        $lists = Model_Listas::find('first', array(
                'where' => array(
                    array('id', $id_item)
                    ),
                ));

        if($lists != null){
            $lists->delete();
        }
            
        return $this->response(array(
                    'code' => 200,
                ));;
    }

}