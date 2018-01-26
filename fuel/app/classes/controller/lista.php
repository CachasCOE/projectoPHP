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

	public function post_addSongToList(){
		$jwt = apache_request_headers()['Authorization'];
		try{
			$tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

			$input = $_POST;
			$id = $tokenDecode->data->id;

			$id_list = $input['id_list'];
			$id_song = $input['id_song'];

			$BDlistasTienen = Model_ListasTienen::find('first', array(
				'where' => array(
					array('id_lista', $id_list),
					array('id_cancion', $id_song)
					),
				));
			$BDCanciones = Model_Canciones::find('first', array(
				'where' => array(
					array('id', $id_song)
					),
				));
			$BDListas = Model_Listas::find('first', array(
				'where' => array(
					array('id', $id_list)
					),
				));
			$BDUser = Model_Usuarios::find('first', array(
				'where' => array(
					array('id', $id)
					),
				));
			if($BDUser != null){
				if($BDCanciones != null){
					if($BDListas != null){
						if($BDlistasTienen == null){
							$new = new Model_ListasTienen();
							$new->id_lista = $id_list;
							$new->id_cancion = $id_song;
							$new->save();
							$this->Mensaje('200', 'Cancion añadida a lista', $id_song);
						} else {
							$this->Mensaje('400', 'Cancion ya esta en lista', $id_song);
						}
					} else {
						$this->Mensaje('400', 'Lista no existe', $id_list);
					}
				} else {
					$this->Mensaje('400', 'Cancion no existe', $id_song);
				}
			} else {
				$this->Mensaje('400', 'Usuario no valido', $id);
			}
		} catch(Exception $e) {
			$this->Mensaje('500', 'Error de verificacion', "aprender a programar");
		} 

	}

	public function post_deleteSongFromList(){
			$jwt = apache_request_headers()['Authorization'];
			try{
				$input = $_POST;
				$id_song = $input['id_song'];
				$id_list = $input['id_list'];

				$tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

				$id = $tokenDecode->data->id;

				$BDlistasTienen = Model_ListasTienen::find('first', array(
						'where' => array(
							array('id_lista', $id_list),
							array('id_cancion', $id_song)
							),
						));
				$BDUser = Model_Usuarios::find('first', array(
				'where' => array(
					array('id', $id)
					),
				));
				if($BDUser != null){
					if($BDlistasTienen != null){
						$BDlistasTienen->delete();
						$this->Mensaje('200', 'cancion borrada de lista', $input);
					} else {
						$this->Mensaje('400', 'cancion no esta en lista', $input);
					}
				} else {
					$this->Mensaje('400', 'usuario no valido', $id);
				}
			} catch(Exception $e) {
				$this->Mensaje('500', 'Error de verificacion', "aprender a programar");
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