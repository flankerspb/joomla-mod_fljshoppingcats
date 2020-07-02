<?php

defined('_JEXEC') or die();

function recursiveCats(&$categories, $id)
{
	static $level = 0;
	
	$html = $level ? '<ul class="uk-nav-sub uk-nav-side">' : '<ul class="uk-nav uk-nav-parent-icon uk-nav-side" data-uk-nav="{multiple:true}">';
	
	foreach($categories[$id] as $cat)
	{
		if($categories[$cat->id])
		{
			$html .= $cat->active ? '<li class="uk-parent uk-active">' : '<li class="uk-parent">';
			
			$html .= JHtml::link($cat->link, $cat->name, 'class="fl-link"');
			$html .= JHtml::link('#', '', 'class="fl-toggle"');
			
			$level++;
			$html .= recursiveCats($categories, $cat->id);
			$level--;
		}
		else
		{
			$html .= $cat->active ? '<li class="uk-active">' : '<li>';
			$html .= JHtml::link($cat->link, $cat->name, 'class="fl-link"');
		}
		
		$html .= '</li>';
	}
	
	return $html . '</ul>';
}

$html = '<div class = "uk-panel uk-panel-box">';
$html .= '<h3 class="uk-panel-title">Sub Menu</h3>';
$html .= recursiveCats($categories, 0);
$html .= '</div>';

echo $html;