<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_pankylottiefiles
 *
 * @copyright   (C) 2022 Panayiotis Kiriakopoulos. <https://github.com/pnkr>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$modId 	= 'mod_pankylottiefiles' . $module->id;

?>


<?php if($params->get('jsonlottie')) { //run the code below only if there is a lottie file inserted ?> 
<div id="<?php echo $modId; ?>" class="mod_pankylottiefiles">
	<lottie-player 
		src="<?php echo $params->get('jsonlottie'); ?> " 
		background="<?php echo $params->get('backgroundcolor', 'transparent'); ?> " 
		speed="<?php echo $params->get('speed', '1'); ?> " 
		playmode="<?php echo $params->get('playmode', 'normal'); ?> " 
		direction="<?php echo $params->get('direction', 'forwards'); ?> " 
		<?php echo " " . $params->get('autoplay', 'no-autoplay'); ?> 
		<?php echo " " . $params->get('loop', 'no-loop'); ?> 
		<?php echo " " . $params->get('hover', 'no-hover'); ?>  
		<?php echo " " . $params->get('controls', 'no-controls'); ?> 
		 style="width: <?php echo $params->get('width'); ?>; height: <?php echo $params->get('height'); ?>;">
	</lottie-player>
	<?php echo $module->content; ?>
</div>
<?php } ?>