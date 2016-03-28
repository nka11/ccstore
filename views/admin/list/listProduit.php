<?php ob_start();?>

	<div class='boutonADD'><a href='post.php?what=produit&action=ajouter'>AJOUTER</a></div>
<table>
<tr>
	<th>Titre</th>
	<th>Prix d'achat</th>
	<th>Prix vente</th>		
	<th>TVA</th>
	<th>Producteur</th>
	<th>Categorie</th>
	<th>Description</th>
	<th>Activation</th>
	<th>Image</th>
	<th>Actions</th>
</tr>
<?php if(is_array($t)):?>
<?php foreach($t as $att){?>
<tr>
	<td><?php echo $att->titre();?></td>
	<td><?php echo $att->prix_achat()?></td>
	<td><?php echo $att->prix_vente();?></td>
	<td><?php echo $att->tva();?></td>
	<td><?php echo $att->producteur()->denom();?></td>
	<td><?php echo $att->categorie()->tag();?></td>
	<td><?php echo $att->description();?></td>
	<td><?php if($att->is_active()){echo 'oui';}else{echo 'non';}?></td>
	<td><?php echo $att->img();?></td>
	
	<td><a href='post.php?what=produit&where=<?php echo $att->id_p(); ?>&action=modifier'>modifier</a> | <a href='post.php?what=produit&action=supprimer&where=<?php echo $att->id_p();?>'>supprimer</a></td>
</tr>
<?php }?>
<?php endif;?>
</table>

<p><?php if(isset($formAnswer)) echo $formAnswer;?></p>



<?php $view_section= ob_get_clean();?>