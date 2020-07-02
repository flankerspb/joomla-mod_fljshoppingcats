<?php
/**
 * @version      1.0.1
 * @author       Vitaliy Moskalyuk
 * @subpackage  mod_fljshopcats
 * @copyright    Copyright (C) 2018 Vitaliy Moskalyuk. All rights reserved.
 * @license      GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die();

if(!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php'))
{
	JError::raiseError(500,'Joomshopping component not installed');
}

JLoader::register('ModFlJShopCatsHelper', __DIR__ . '/helper.php');

$input = JFactory::getApplication()->input->getArray();
// $params->offsetSet('current_category', JRequest::getInt('category_id'));

$module->title = $params->get('title');

$cacheid = md5($module->id . rtrim(Juri::getInstance()->getPath(), '.html'));

$cacheparams               = new stdClass;
$cacheparams->cachemode    = 'id';
$cacheparams->class        = 'ModFlJShopCatsHelper';
$cacheparams->method       = 'getAllCats';
$cacheparams->methodparams = $params;
$cacheparams->modeparams   = $cacheid;

$categories = JModuleHelper::moduleCache($module, $params, $cacheparams);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_fljshoppingcats', $params->get('layout', 'default'));
