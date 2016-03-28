<?php ob_start();?>

	<div class='boutonADD'><a href='post.php?what=commande&action=ajouter'>AJOUTER</a></div>
<table>
<tr>
	<th>Client</th>
	<th>Date création</th>
	<th>Date livraison</th>		
	<th>Mode livraison</th>
	<th>Montant</th>
	<th>Statut</th>
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
	
	<td><a href='post.php?what=client&where=<?php echo $att->id_p(); ?>&action=modifier'>modifier</a> | <a href='post.php?what=client&action=supprimer&where=<?php echo $att->id_p();?>'>supprimer</a></td>
</tr>
<?php }?>
<?php endif;?>
</table>

<p><?php if(isset($formAnswer)) echo $formAnswer;?></p>



<?php $view_section= ob_get_clean();?>