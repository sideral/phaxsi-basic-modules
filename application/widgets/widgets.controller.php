<?php

class WidgetsController extends Controller{

	function widgets_setup(){
		$this->layout = '';
		//$this->plugin->Auth->authorize('admin-area', array('uploadimage'), 'exit');
	}

	function uploadImage(){

		$form = $this->load->form('/phaxsi/form', $_POST);
		$image = $form->add('fileimage', 'userfile');
		$filenames = $image->createThumbs(array($_POST['thumb'], '{uploads['.DEFAULT_MODULE.']}/images/{random}.{ext}', $_POST['width'], $_POST['height']));

		if($filenames){
			//Uploads?
			$filename = UrlHelper::resource(str_replace(APPD_PUBLIC, '', $filenames[0]));
			$this->view->set('result', $filename);
			$this->view->set('resultcode', 'ok');
			$this->view->set('file_name', $filename);
		}
		else{
			$this->view->set('result', 'Upload failed');
			$this->view->set('resultcode', 'failed');
			$this->view->set('file_name', '');
		}

	}

	function blank(){

	}

}
