<?php ob_start();?>

<form class='form_admin' action='post.php?what=producteur' method='POST'>

<input id='denom' name='denom' placeholder='Denomination' type='text' value='<?php if(isset($t)) echo $t->denom();?>'/>
<input id='titre' name='titre' placeholder='Structure' type='text' value='<?php if(isset($t)) echo $t->titre();?>'/>
<input id='adresse' name='adresse' placeholder='Adresse, ville' type='text' value='<?php if(isset($t)) echo $t->adresse();?>'/>
<input id='departement' name='departement' placeholder='Departement' type='text' value='<?php if(isset($t)) echo $t->departement();?>'/>
<input id='telephone' name='telephone' placeholder='Telephone' type='text' value='<?php if(isset($t)) echo $t->telephone();?>'/>
<input id='description' name='description' placeholder='Description' type='text' value='<?php if(isset($t)) echo $t->description();?>'/>

<?php	if(isset($t)):?>
		<input type='hidden' value='<?php echo $t->id_pro();?>' name='id_pro'/>
<?php endif;?>
<!--<input type='hidden' value='<?php echo date('Y');?>' name='annee'/>-->
<input type='hidden' value='<?php echo $table; ?>' name='table'/>
<input type='submit' value='<?php echo $action; ?>' name='Enregistrer'/>

</form>

<?php $view_section = ob_get_clean();?>