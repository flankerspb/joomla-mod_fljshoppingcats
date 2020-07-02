<?php
defined('_JEXEC') or die;

class ModFlJShopCatsHelper
{
	public static function getAllCats($params)
	{
		static $tree = array();
		
		if(count($tree))
		{
			return $tree;
		}
		
		$publish = 1;
		$access = 1;
		$listType = 1;
		
		$active = JRequest::getInt('category_id');
		
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$lang = JSFactory::getLang();
		
		$where = array();
		
		if($publish)
		{
			$where[] = "category_publish = '1'";
		}
		
		if($access)
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$where[] =' access IN ('.$groups.')';
		}
		
		$add_where = "";
		
		if (count($where))
		{
			$add_where = " where ".implode(" and ", $where);
		}
		
		if($listType=='id')
		{
			$query = "SELECT category_id, category_parent_id FROM `#__jshopping_categories` ".$add_where." ORDER BY ordering";
		}
		else
		{
			$query = "SELECT
			`".$lang->get('alias')."` as alias,".
			"`".$lang->get('name')."` as name,
			category_id as id,
			category_parent_id as parent_id,
			category_image as image,
			category_publish as publish
			FROM `#__jshopping_categories`
			".$add_where." ORDER BY category_parent_id, ordering";
		}
		
		$db->setQuery($query);
		$categories =  $db->loadObjectList('id');
		
		$levels = array();
		
		foreach($categories as $key => $value)
		{
			if($key == $active)
			{
				$categories[$key]->active = 1;
				self::setActiveParents($categories, $key);
			}
			
			$categories[$key]->link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$categories[$key]->id, 1);
			
			if($value->parent_id == 0)
			{
				$categories[$key]->level = 0;
				$levels[$key] = 0;
			}
			else
			{
				$levels[$key] = $levels[$value->parent_id] + 1;
				$categories[$key]->level = $levels[$key];
			}
			
			$tree[$value->parent_id][] = $value;
		}
		
		return $tree;
	}
	
	function setActiveParents(&$categories, $id)
	{
		$parent_id = $categories[$id]->parent_id;
		
		if($parent_id != 0)
		{
			$categories[$parent_id]->active = 1;
			self::setActiveParents($categories, $parent_id);
		}
	}
}