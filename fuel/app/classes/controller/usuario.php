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
                    'code' => 500,
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
                    'code' => 500,
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
                    'code' => 500,
                    'message' => 'Songs not found'
                ));
        }
    }

    public function post_createList()
    {
        $input = $_POST;
        $jwt = apache_request_headers()['Authorization'];
        $title = $input['title'];
        //$id_usuario = $input['id_usuario'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

         $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));

        if($BDuser != null){
            $new = new Model_Listas();
            $new->title = $input['title'];
            $new->id_usuario = $id;
            $new->save();

            $json = $this->response(array(
                    'response' => 200,
                    'message' => 'lista creada',
                    'title' => $input['title'],
                ));
        }else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Invalid user',
                    'data' => 'empty'
                ));
        }
           return $json;
    }

    public function get_lists(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
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
                    'message' => 'lista de listas',
                    'data' => $lists
                ));
        }else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Invalid user',
                    'data' => 'empty'
                ));
        }
           return $json;
    }

    public function post_deleteList(){
        $jwt = apache_request_headers()['Authorization'];
        $input = $_POST;
        $id_item = $input['id_item'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));
        if(count($BDuser) == 1){
            $lists = Model_Listas::find('all', array(
                    'where' => array(
                        array('id_usuario', $id ),
                        array('id', $id_item)
                        ),
                    ));

            if($lists != null){
                $lists->delete();
            }
            
            $json = $this->response(array(
                        'code' => 200,
                        'message' => 'List delete',
                        'data' => $lists
                    ));;
        } else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Songs not found'
                ));
        }
    }

    public function post_modifyList(){
        $input = $_POST;
        $jwt = apache_request_headers()['Authorization'];
        $title = $input['title'];
        $id_item = $input['id_item'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));

        if($BDuser != null){
            $lists = Model_Listas::find('first', array(
                    'where' => array(
                        array('id_usuario', $id),
                        array('id', $id_item)
                        ),
                    ));

            if($lists != null){
                $lists->title = $input['title'];
                $lists->save();
            }
            
            $json = $this->response(array(
                        'code' => 200,
                        'message' => 'List modified',
                        'data' => $lists
                    ));;
        } else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Invalid user'
                ));
        }
           return $json; 
    }

    public function post_createSong(){
        $input = $_POST;
        $jwt = apache_request_headers()['Authorization'];
        $titulo = $input['titulo'];
        $artista = $input['artista'];
        $direccion = $input['direccion'];
        //$id_usuario = $input['id_usuario'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));
        if($BDuser != null){
            $new = new Model_Canciones();
            $new->titulo = $input['titulo'];
            $new->direccion_youtube = $input['direccion'];
            $new->artista = $input['artista'];
            $new->save();

            $json = $this->response(array(
                    'response' => 200,
                    'message' => 'cancion creada',
                    'titulo' => $input['titulo'],
                ));
        } else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Couldnt create song(User not found)',
                    'data' => 'empty'
                ));
        }
        return $json;
    }

    public function get_Songs(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));

        if($BDuser != null){
            $users = Model_Canciones::find('all');
            $json = $this->response(array(
                    'code' => 200,
                    'message' => 'lista de canciones',
                    'data' => $users
                ));
        }else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Songs not found',
                    'data' => 'empty'
                ));
        }
            
        return $json;
        
    }

    public function post_modifySong(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

        $input = $_POST;
        $id_item = $input['id_item'];
        $url = $input['url'];
        $titulo = $input['titulo'];
        $artista = $input['artista'];

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));

        if($BDuser != null){
            $songSearch = Model_Canciones::find('first', array(
                'where' => array(
                    array('id', $id_item)
                ),
            ));
            if($songSearch != null){
                if (empty($url) && empty($titulo) && !empty($artista)) {
                    $songSearch->artista = $input['artista'];
                    $songSearch->save();
                }
                if (empty($url) && !empty($titulo) && empty($artista)) {
                    $songSearch->titulo = $input['titulo'];
                    $songSearch->save();
                }
                if (!empty($url) && empty($titulo) && empty($artista)) {
                    $songSearch->direccion_youtube = $input['url'];
                    $songSearch->save();
                }
                if (!empty($url) && !empty($titulo) && empty($artista)) {
                    $songSearch->direccion_youtube = $input['url'];
                    $songSearch->titulo = $input['titulo'];
                    $songSearch->save();
                }
                if (!empty($url) && empty($titulo) && !empty($artista)) {
                    $songSearch->direccion_youtube = $input['url'];
                    $songSearch->artista = $input['artista'];
                    $songSearch->save();
                }
                if (empty($url) && !empty($titulo) && !empty($artista)) {
                    $songSearch->artista = $input['artista'];
                    $songSearch->titulo = $input['titulo'];
                    $songSearch->save();
                }
                if (!empty($url) && !empty($titulo) && !empty($artista)) {
                    $songSearch->direccion_youtube = $input['url'];
                    $songSearch->artista = $input['artista'];
                    $songSearch->titulo = $input['titulo'];
                    $songSearch->save();
                }
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'song modificado',
                    'data' => $songSearch
                ));;
            }
            
        } else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Invalid user'
                ));
        }
           return $json;
    }

    public function post_deleteSong(){
        $jwt = apache_request_headers()['Authorization'];

        $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
        
        $id = $tokenDecode->data->id;

        $input = $_POST;

        $BDuser = Model_Usuarios::find('first', array(
        'where' => array(
            array('id', $id)
            ),
        ));
        if($BDuser != null){
            $song = Model_Canciones::find('first', array(
                'where' => array(
                    array('id', $input['id'])
                ),
            ));
            if ($song != null) {
                $song->delete();
                $json = $this->response(array(
                        'code' => 200,
                        'message' => 'SOng deleted',
                        'data' => $song
                    ));;
            }else {
                $json = $this->response(array(
                        'code' => 400,
                        'message' => 'SOng not found',
                        'data' => $song
                    ));;
            }
            
            
        } else {
            $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Songs not found'
                ));
        }
    }

}