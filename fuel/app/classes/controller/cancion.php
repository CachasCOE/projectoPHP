<?php
use Firebase\JWT\JWT;
class Controller_lista extends Controller_Rest
{
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
				'code' => 400,
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
				'code' => 400,
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
				'code' => 400,
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
					'message' => 'Song deleted',
					'data' => $song
				));;
			}else {
				$json = $this->response(array(
					'code' => 400,
					'message' => 'Song not found',
					'data' => $song
				));;
			}


		} else {
			$json = $this->response(array(
				'code' => 400,
				'message' => 'Songs not found'
			));
		}
	}
}