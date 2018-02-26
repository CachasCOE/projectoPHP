<?php
use Firebase\JWT\JWT;
class Controller_noticias extends Controller_Rest
{
	private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_createNews(){
		$input = $_POST;
		$jwt = apache_request_headers()['Authorization'];
        //$id_usuario = $input['id_usuario'];

		$tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

		$id = $tokenDecode->data->id;

		$BDuser = Model_Usuarios::find('first', array(
			'where' => array(
				array('id', $id)
			),
		));

		if($BDuser != null){
			$new = new Model_Noticias();
			$new->title = $input['title'];
			$new->descripcion = $input['descripcion'];
			$new->id_usuario = $id;
			$new->save();

			$this->Mensaje('200', 'noticia añadida', $input);
		} else {
			$this->Mensaje('400', 'user not found', $input);
		}
	}

	public function post_modifyNews(){
		$jwt = apache_request_headers()['Authorization'];

		$tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

		$id = $tokenDecode->data->id;

		$input = $_POST;
		$id_item = $input['id_item'];
		$descripcion = $input['descripcion'];
		$title = $input['title'];
		$id_usuario = $input['id_usuario'];

		$BDuser = Model_Usuarios::find('first', array(
			'where' => array(
				array('id', $id)
			),
		));

		if($BDuser != null){
			$newsSearch = Model_Noticias::find('first', array(
				'where' => array(
					array('id', $id_item)
				),
			));
			if($newsSearch != null){
				if (empty($descripcion) && empty($title) && !empty($id_usuario)) {
					$newsSearch->id_usuario = $input['id_usuario'];
					$newsSearch->save();
				}
				if (empty($descripcion) && !empty($title) && empty($id_usuario)) {
					$newsSearch->title = $input['title'];
					$newsSearch->save();
				}
				if (!empty($descripcion) && empty($title) && empty($id_usuario)) {
					$newsSearch->descripcion = $input['descripcion'];
					$newsSearch->save();
				}
				if (!empty($descripcion) && !empty($title) && empty($id_usuario)) {
					$newsSearch->descripcion = $input['descripcion'];
					$newsSearch->title = $input['title'];
					$newsSearch->save();
				}
				if (!empty($descripcion) && empty($title) && !empty($id_usuario)) {
					$newsSearch->descripcion = $input['descripcion'];
					$newsSearch->id_usuario = $input['id_usuario'];
					$newsSearch->save();
				}
				if (empty($descripcion) && !empty($title) && !empty($id_usuario)) {
					$newsSearch->id_usuario = $input['id_usuario'];
					$newsSearch->title = $input['title'];
					$newsSearch->save();
				}
				if (!empty($descripcion) && !empty($title) && !empty($id_usuario)) {
					$newsSearch->descripcion = $input['descripcion'];
					$newsSearch->id_usuario = $input['id_usuario'];
					$newsSearch->title = $input['title'];
					$newsSearch->save();
				}
				$this->Mensaje('200', 'Noticia modificada', $newsSearch);
			}

		} else {
			$this->Mensaje('400', 'Usuario no valido', $id);
		}
	}

	public function post_deleteNew(){
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
			$lists = Model_Noticias::find('first', array(
				'where' => array(
					array('id_usuario', $id ),
					array('id', $id_item)
				),
			));

			if($lists != null){
				$lists->delete();

				$this->Mensaje('200', 'noticia borrada', $lists);
			}else{
				$this->Mensaje('400', 'noticia no encontrada', $lists);
			}
		} else {
			$this->Mensaje('400', 'usuario no valido', $id);
		}
	}

	public function get_getOwnNews(){
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
			$lists = Model_Noticias::find('all', array(
				'where' => array(
					array('id_usuario', $id)
				),
			));
			$this->Mensaje('200', 'lista de noticias', $lists);
		}else {
			$this->Mensaje('400', 'usuario no valido', $id);
		}
	}

	public function get_getNew(){
		$jwt = apache_request_headers()['Authorization'];

		$tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

		$username = $tokenDecode->data->username;
		$password = $tokenDecode->data->password;
		$id = $tokenDecode->data->id;

		$title = $_GET['title'];

		$BDuser = Model_Usuarios::find('all', array(
			'where' => array(
				array('username', $username),
				array('password', $password)
			),
		));
		
		if(count($BDuser) == 1){
			$new = Model_Noticias::find('all', array(
				'where' => array(
					array('title', $title)
				),
			));
			$this->Mensaje('200', 'Noticia', $new);
		}else {
			$this->Mensaje('400', 'usuario no valido', $id);
		}
	}

	public function get_getNews(){
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
			$lists = Model_Noticias::find('all');
			$this->Mensaje('200', 'lista de noticias', $lists);
		}else {
			$this->Mensaje('400', 'usuario no valido', $id);
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