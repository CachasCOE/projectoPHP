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
		$editable = $input['editable'];
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
			$new->editable = $input['editable'];
			$new->save();

			$this->Mensaje('200', 'lista creada', $input['title']);
		}else {
			$this->Mensaje('400', 'usuario no valido', $jwt);
		}
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
			$this->Mensaje('200', 'lista de listas', $lists);
		}else {
			$this->Mensaje('400', 'usuario no valido', $id);
		}
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
			$lists = Model_Listas::find('first', array(
				'where' => array(
					array('id_usuario', $id ),
					array('id', $id_item)
				),
			));

			if($lists != null){
				$lists->delete();

				$this->Mensaje('200', 'lista borrada', $lists);
			}else{
				$this->Mensaje('400', 'lista no encontrada', $lists);
			}
		} else {
			$this->Mensaje('400', 'usuario no valido', $id);
		}
	}

	public function post_modifyList(){

		$input = $_POST;
		$jwt = apache_request_headers()['Authorization'];

		if (array_key_exists('title', $input)&& array_key_exists('id_item', $input) && array_key_exists('editable', $input)) {

			$title = $input['title'];
			$id_item = $input['id_item'];
			$editable = $input['editable'];
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
					$lists->title = $title;
					$lists->editable = $editable;
					$lists->save();


					$this->Mensaje('200', 'lista modificada', $lists);
				}else{
					$this->Mensaje('400', 'no tienes acceso a esa lista', $lists);
				}
			} else {
				$this->Mensaje('400', 'usuario no valido', $lists);
			}

		}else {
			$this->Mensaje('400', 'parametros invalidos', $input);
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