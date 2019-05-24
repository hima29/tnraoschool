<?php
class mdocs_box_view {
	public function displayThumbnail($thumbnail) {
		if(function_exists('imagecreatefromjpeg')) {
			@$im = imagecreatefromstring($thumbnail);
			if ($im !== false) {
				ob_start();
				imagepng($im);
				$png = ob_get_clean();
				$uri = "data:image/png;base64," . base64_encode($png);
				imagedestroy($im);
				echo $uri;
			}
			else {
				echo 'Thumbnail data not available.';
			}
		} else echo 'php-gd is unsupported on this server';
	}
	public function getThumbnail($doc_id, $the_mdoc) {
		//GET THE THUMBNAIL URL
		$header = array(
			'Authorization: Bearer '.get_option('mdocs-box-view-key'),
			'x-rep-hints: [jpg?dimensions=320x320]',
			'Content-Type:multipart/form-data',
		);
		$url = 'https://api.box.com/2.0/files/'.$doc_id.'?fields=representations';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		if($data['type'] != 'error') {
			// GENERATE THE THUMBNAIL
			$the_thumbnail = $data['representations']['entries'][0];
			$header = array(
				'Authorization: Bearer '.get_option('mdocs-box-view-key'),
			);
			$url = str_replace('{+asset_path}', '',$the_thumbnail['content']['url_template']);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
			curl_close($ch);
			$boxview = new mdocs_box_view();
			$boxview->displayThumbnail($data);
		} else {
			$upload_dir = wp_upload_dir();
			$is_image = @getimagesize($upload_dir['basedir'].'/mdocs/'.$the_mdoc['filename']);
			if($is_image == false && strtolower($the_mdoc['type']) != 'zip' && strtolower($the_mdoc['type']) != 'rar') {
				if($data['code'] == 'not_found') {
					$boxview = new mdocs_box_view();
					$the_upload = $boxview->uploadFile($the_mdoc);
					$the_mdoc['box-view-id'] = $the_upload['entries']['0']['id'];
					$mdocs = get_option('mdocs-list');
					foreach($mdocs as $index => $doc) {
						if($the_mdoc['id'] == $doc['id']) {
							$mdocs[$index] = $the_mdoc;
							break;
						}
					}
					update_option('mdocs-list', $mdocs);
					sleep(10);
					$boxview->getThumbnail($the_mdoc['box-view-id'], $the_mdoc);
				}
			}
			
		}
	}
	public function downloadFile($the_mdoc) {
		$header = array(
			'Authorization: Bearer '.get_option('mdocs-box-view-key'),
			'Content-Type:multipart/form-data',
		);
		$url = 'https://api.box.com/2.0/files/'.$the_mdoc['box-view-id'].'?fields=expiring_embed_link';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		
		
		$is_image = @getimagesize($upload_dir['basedir'].'/mdocs/'.$the_mdoc['filename']);
		if($is_image == false && strtolower($the_mdoc['type']) != 'zip' && strtolower($the_mdoc['type']) != 'rar') {
			if($data['type'] == 'error') {
				if($data['code'] == 'not_found') {
					$boxview = new mdocs_box_view();
					$the_upload = $boxview->uploadFile($the_mdoc);
					$the_mdoc['box-view-id'] = $the_upload['entries']['0']['id'];
					$mdocs = get_option('mdocs-list');
					foreach($mdocs as $index => $doc) {
						if($the_mdoc['id'] == $doc['id']) {
							$mdocs[$index] = $the_mdoc;
							break;
						}
					}
					update_option('mdocs-list', $mdocs);
					$new_preview = $boxview->downloadFile($the_mdoc);
					return $new_preview;
				}
				return $data;
			} else return $data;
		} return array('type' => 'error', 'status' => '500', 'message' => 'This file type is not supported yet.');
	}
	public function uploadFile($the_file, $thumbnail_size='256x256', $try=0) {
		$upload_dir = wp_upload_dir();
		$header = array(
			'Authorization: Bearer '.get_option('mdocs-box-view-key'),
			'Content-Type:multipart/form-data',
		);
		$url = 'https://upload.box.com/api/2.0/files/content';
		$attributes = array(
			'name' => $the_file['filename'],
			'parent'=> array('id' => 0),
		);
		$params = array('attributes' => json_encode($attributes), 'file' => new CurlFile($upload_dir['basedir'].'/mdocs/'.$the_file['filename'],$the_file['type'],$the_file['filename']));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec ($ch);
		$data = json_decode($result,true);
		curl_close ($ch);
		return $data;
	}
	public function deleteFile($the_file) {
		// GET ETAG
		$header = array(
			'Authorization: Bearer '.get_option('mdocs-box-view-key'),
		);
		$url = 'https://api.box.com/2.0/files/'.$the_file['box-view-id'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		$data = json_decode($data);
		curl_close($ch);
		// DELETE FILE
		$header = array(
			'Authorization: Bearer '.get_option('mdocs-box-view-key'),
			'If-Match: '.$data->etag,
		);
		$url = 'https://api.box.com/2.0/files/'.$the_file['box-view-id'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		curl_close($ch);
		// DELETE FILE FROM TRASH
		$header = array(
			'Authorization: Bearer '.get_option('mdocs-box-view-key'),
		);
		$url = 'https://api.box.com/2.0/files/'.$the_file['box-view-id'].'/trash';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		curl_close($ch);
	}
}
?>