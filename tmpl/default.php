<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_pankylottiefiles
 *
 * @copyright   (C) 2022 Panayiotis Kiriakopoulos. <https://github.com/pnkr>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

$modId 	= 'mod_pankylottiefiles' . $module->id;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerScript('pankylottiefiles.script', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js');
$wa->useScript('pankylottiefiles.script');

if ($params->get('backgroundimage'))
{

	$wa->addInlineStyle('
#' . $modId . '{background-image: url("' . Uri::root(true) . '/' . HTMLHelper::_('cleanImageURL', $params->get('backgroundimage'))->url . '");}
', ['name' => $modId]);
};

?>

<div id="<?php echo $modId; ?>" class="mod_pankylottiefiles">
<lottie-player 
	src="<?php echo $params->get('jsonlottie'); ?>"  
	background="<?php echo $params->get('backgroundcolor','transparent'); ?>"  
	speed="<?php echo $params->get('speed','1'); ?>"  
	style="width: <?php echo $params->get('width'); ?>; height: <?php echo $params->get('height'); ?>;"  
	<?php 
		if($params->get('loop')){
			echo " loop ";
		};
		if($params->get('controls')){
			echo " controls ";
		};
		if($params->get('autoplay')){
			echo " autoplay ";
		};
	?>
>
</lottie-player>
	<?php echo $module->content; ?>
</div>
