<?php
	$status = 0;
	
	include_once('engine.php');

	$engine = new Engine;
	
	if (isset($_POST['json'])) {
		$json = $_POST['json'];
		$post = json_decode($json, true);
		
		$title = $post['title'];
		$body = $post['body'];
		$category = $post['category'];
		$type = $post['type'];
		$mode = $post['mode'];
		$id = $post['id'];
		
		if (empty($title) || empty($body) || empty($type) || empty($category)) {
			$status = -1;
		} else {
			$postdata = array();
			switch ($type) {
				case 'textpost':
					$rvalue = $engine->getUserID();
					if ($rvalue != Engine::DATABASE_ERROR_NO_QUERY_RESULTS || $rvalue != Engine::DATABASE_ERROR_COULD_NOT_ACCESS_DATABASE) {
						$postdata['type'] = Engine::FEATURE_SUPPORT_TEXT_POST;
						$postdata['title'] = $title;
						$postdata['details'] = $body;
						$postdata['author'] = $rvalue;
						$postdata['category'] = $category;
						$postdata['id'] = $id;
						
						if ($mode == 1) {
							$rvalue = $engine->submitNewPost($postdata);
						} else if ($mode == 2) {
							$rvalue = $engine->editExistingPost($postdata);
						}
						
						if ($rvalue == Engine::DATABASE_ERROR_NO_ERROR) {
							$status = 1;
						} else {
							$status = -2;
						}
					} else {
						$status = -2;
					}
					
					break;	
			}
		}
	}
	
	$json_data = array('status' => $status
		);
	$json_encoded = json_encode($json_data, JSON_FORCE_OBJECT);
	
	header('Content-type: application/json');
	echo $json_encoded;
?>