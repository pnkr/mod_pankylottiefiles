<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_pankylottiefiles
 *
 * @copyright   (C) 2022 Panayiotis Kiriakopoulos. <https://github.com/pnkr>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Factory ;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa **/
$doc = Factory::getApplication()->getDocument();
$wa = $doc->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_pankylottiefiles');
$wa->useScript('pankylottiefiles','script');

if ($params->def('prepare_content', 1))
{
	PluginHelper::importPlugin('content');
	$module->content = HTMLHelper::_('content.prepare', $module->content, '', 'mod_pankylottiefiles.content');
}
//load view
require ModuleHelper::getLayoutPath('mod_pankylottiefiles', $params->get('layout', 'default'));
