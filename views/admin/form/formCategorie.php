<?php ob_start();?>

<form class='form_admin' action='post.php?what=categorie' method='POST'>

<input id='tag' name='tag' placeholder='tag' type='text' value='<?php if(isset($t)) echo $t->tag();?>'/>
<select id='id_parent'	name='id_parent'>
	<option value='' selected></option>
	<?php foreach($listCat as $cat):?>
		<option value='<?php echo $cat->tag();?>'><?php echo $cat->tag();?></option>
	<?php endforeach;?>
</select>

<?php	if(isset($t)):?>
		<input type='hidden' value='<?php echo $t->id_cat();?>' name='id_cat'/>
<?php endif;?>
<!--<input type='hidden' value='<?php echo date('Y');?>' name='annee'/>-->
<input type='hidden' value='<?php echo $table; ?>' name='table'/>
<input type='submit' value='<?php echo $action; ?>' name='Enregistrer'/>


</form>

<?php $view_section = ob_get_clean();?>