<?php
use Firebase\JWT\JWT;
class Controller_lista extends Controller_Rest
{
	private $key = 'my_secret_key';
	protected $format = 'json';

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
				'code' => 400,
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
				'code' => 400,
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

				$json = $this->response(array(
					'code' => 200,
					'message' => 'List delete',
					'data' => $lists
				));;
			}else{
				$json = $this->response(array(
				'code' => 400,
				'message' => 'List not found'
				));
			}
		} else {
			$json = $this->response(array(
				'code' => 400,
				'message' => 'user not found'
			));
		}
	}

	public function post_modifyList(){

		$input = $_POST;
		$jwt = apache_request_headers()['Authorization'];

		if (array_key_exists('title', $input)&& array_key_exists('id_item', $input)) {

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
				$lists = Model_Listas::find('all', array(
					'where' => array(
						array('id_usuario', $id),
						array('id', $id_item)
					),
				));

				if($lists != null){
					$lists->title = $input['title'];
					$lists->save();


					$json = $this->response(array(
						'code' => 200,
						'message' => 'List modified',
						'data' => $lists
					));;
				}else{
					$json = $this->response(array(
						'code' => 400,
						'message' => 'You dont have access to that list'
					));
				}
			} else {
				$json = $this->response(array(
					'code' => 400,
					'message' => 'Invalid user'
				));
			}

		}else {
			$json = $this->response(array(
				'code' => 400,
				'message' => 'invalid parameters'
			));
		}
		return $json;
	}
}