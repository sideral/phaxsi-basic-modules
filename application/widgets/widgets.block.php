<?php

class WidgetsBlock extends Block{

	function pagination(){

		$this->helper->filter->defaults($this->args,
			array(
				'items_per_page' => 10,
				'item_count' => 0,
				'visible_pages' => 10,
				'link_url' => UrlHelper::currentPath(true, array('page' => '###')),
				'url_page_marker' => '###',
				'current_page' => 1,
				'show_single_page' => false,
				'show_arrows' => false,
				'show_pages' => true,
				'css_class' => 'pagination',
				'left_arrow' => '&lt;',
				'right_arrow' => '&gt;',
				'more_pages' => '...',
				'id' => HtmlHelper::generateId()
			)
		);

		$max_page = ceil($this->args['item_count']/$this->args['items_per_page']);

		if($this->args['current_page'] > $max_page || $this->args['current_page'] <= 0){
			$this->args['current_page'] = 1;
		}

		$starting_page = floor(($this->args['current_page']-1)/$this->args['visible_pages'])*$this->args['visible_pages'] + 1;
		$final_page = min($max_page, $starting_page + $this->args['visible_pages'] -1);

		$this->view->set('starting_page', $starting_page);
		$this->view->set('max_page', $max_page);
		$this->view->set('final_page', $final_page);

		$this->view->setArray($this->args);

	}

}

