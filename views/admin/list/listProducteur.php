<?php ob_start();?>

	<div class='boutonADD'><a href='post.php?what=producteur&action=ajouter'>AJOUTER</a></div>
<table>
<tr>
	<th>Denomination</th>
	<th>Titre</th>
	<th>Adresse</th>		
	<th>Departement</th>
	<th>Telephone</th>
	<th>Description</th>
	<th>Actions</th>
</tr>
<?php if(is_array($t)):?>
<?php foreach($t as $att){?>
<tr>
	<td><?php echo $att->denom();?></td>
	<td><?php echo $att->titre()?></td>
	<td><?php echo $att->adresse();?></td>
	<td><?php echo $att->departement();?></td>
	<td><?php echo $att->telephone();?></td>
	<td><?php echo $att->description();?></td>
	
	<td><a href='post.php?what=producteur&where=<?php echo $att->id_pro(); ?>&action=modifier'>modifier</a> | <a href='post.php?what=producteur&action=supprimer&where=<?php echo $att->id_pro();?>'>supprimer</a></td>
</tr>
<?php }?>
<?php endif;?>
</table>

<p><?php if(isset($formAnswer)) echo $formAnswer;?></p>



<?php $view_section= ob_get_clean();?>