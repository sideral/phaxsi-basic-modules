<?php
	if(!function_exists('page_link')){
		function page_link($page, $url_page_marker, $link_url){
			$pad = strlen(urldecode($url_page_marker));
			return str_replace($url_page_marker, sprintf('%0'.$pad.'s', $page) , $link_url);
		}
	}	
?>

<div class="<?= $css_class; ?>" id="<?= $id; ?>">

	<?php if($show_arrows && ($max_page > 1 || $show_single_page)): ?>
		<?= $html->link($left_arrow, page_link($current_page-1, $url_page_marker, $link_url) , array('class' => $current_page == 1? 'arrow disabled' : 'arrow'), false); ?>
	<?php endif; ?>

	<?php if($current_page > $visible_pages): ?>
			<?= $html->link($more_pages, page_link(max($starting_page-1,1), $url_page_marker, $link_url) , array('class' => 'more'), false); ?>
	<?php endif;?>

	<?php if($show_pages && ($max_page > 1 || $show_single_page)): ?>
		
		<?php for($i = $starting_page; $i < $final_page + 1; $i++): ?>
			<?= $html->link($i, page_link($i, $url_page_marker, $link_url), array('class' => $current_page == $i? 'page current' : 'page')); ?>
		<?php endfor;?>

	<?php endif; ?>
			

	<?php if($max_page > $final_page): ?>
			<?= $html->link($more_pages, page_link($final_page+1, $url_page_marker, $link_url) , array('class' => 'more'), false); ?>
	<?php endif;?>


	<?php if($show_arrows && ($max_page > 1 || $show_single_page)): ?>
		<?= $html->link($right_arrow, page_link(min($current_page+1, $max_page), $url_page_marker, $link_url) , array('class' => $current_page == $max_page? 'arrow disabled' : 'arrow'), false); ?>
	<?php endif; ?>
		

	<div style="clear:both;line-height:0px;height:0px;"></div>

</div>