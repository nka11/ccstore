<?php ob_start();?>

<form class='form_admin'>

<input id='titre_pro' name='titre_pro' placeholder='titre du produit' type='text' value='<?php if(isset($target)) echo $target->titre();?>'/>




</form>







<?php $view_section = ob_get_clean();?>