<?php ob_start();?>

<form class='form_admin' action='post.php?what=produit' method='POST'>

<input id='titre' name='titre' placeholder='Titre' type='text' value='<?php if(isset($t)) echo $t->titre();?>'/>
<input id='prix_achat' name='prix_achat' placeholder='Prix achat' type='text' value='<?php if(isset($t)) echo $t->prix_achat();?>'/>
<input id='prix_vente' name='prix_vente' placeholder='Prix vente' type='text' value='<?php if(isset($t)) echo $t->prix_vente();?>'/>
<input id='tva' name='tva' placeholder='TVA' type='text' value='<?php if(isset($t)) echo $t->tva();?>'/>
<select id='tag_cat' name='tag_cat'>
	<?php foreach($listCat as $cat):?>
	<option value='<?php echo $cat->tag();?>'><?php echo $cat->tag();?></option>
	<?php endforeach;?>
</select>
<select id='id_producteur' name='id_producteur'>
	<?php foreach($listPro as $pro):?>
	<option value='<?php echo $pro->id_pro();?>'><?php echo $pro->denom();?></option>
	<?php endforeach;?>
</select>
<input id='description' name='description' placeholder='Description' type='text' value='<?php if(isset($t)) echo $t->description();?>'/>
<input id='img' name='img' placeholder='img' type='text' value='<?php if(isset($t)) echo $t->img();?>'/>
<input id='is_active' name='is_active' type='checkbox' value=true/>

<?php	if(isset($t)):?>
		<input type='hidden' value='<?php echo $t->id_p();?>' name='id_p'/>
<?php endif;?>
<!--<input type='hidden' value='<?php echo date('Y');?>' name='annee'/>-->
<input type='hidden' value='<?php echo $table; ?>' name='table'/>
<input type='submit' value='<?php echo $action; ?>' name='Enregistrer'/>

</form>

<?php $view_section = ob_get_clean();?>