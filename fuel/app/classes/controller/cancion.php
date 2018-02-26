<?php
use Firebase\JWT\JWT;
class Controller_cancion extends Controller_Rest
{
	private $key = 'my_secret_key';
    protected $format = 'json';
    
	public function post_createSong(){
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
			$new = new Model_Canciones();
			$new->titulo = $input['titulo'];
			$new->direccion_youtube = $input['direccion'];
			$new->artista = $input['artista'];
			$new->reproductions = '0';
			$new->save();

			$this->Mensaje('200', 'cancion añadida', $input);
		} else {
			$this->Mensaje('400', 'user not found', $input);
		}
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
			$this->Mensaje('200', 'Lista de canciones', $users);
			
		}else {
			$this->Mensaje('400', 'Canciones no encontradas', $users);
		}
	}

	public function post_modifySong(){
		$jwt = apache_request_headers()['Authorization'];

		$tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));

		$id = $tokenDecode->data->id;

		$input = $_POST;
		$id_item = $input['id_item'];

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
				$this->Mensaje('200', 'Canciones modificada', $songSearch);
			}

		} else {
			$this->Mensaje('400', 'Usuario no valido', $id);
		}
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
				$this->Mensaje('200', 'Cancion borrada', $song);
			}else {
				$this->Mensaje('400', 'cancion no encontrada', $song);
			}


		} else {
			$this->Mensaje('400', 'Usuario no valido', $id);
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