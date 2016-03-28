<?php ob_start();?>

<span><?php echo $user->login();?></span>

<?php $view_boxUser = ob_get_clean();?>

	