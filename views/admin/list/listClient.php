<?php ob_start();?>

	<div class='boutonADD'><a href='post.php?what=client&action=ajouter'>AJOUTER</a></div>
<table>
<tr>
	<th>Nom</th>
	<th>Prenom</th>
	<th>Email</th>		
	<th>Adresse</th>
	<th>Code postal</th>
	<th>Ville</th>
	<th>Departement</th>
	<th>Telephone</th>
	<th>Actions</th>
</tr>
<?php if(is_array($t)):?>
<?php foreach($t as $att){?>
<tr>
	<td><?php echo $att->nom();?></td>
	<td><?php echo $att->prenom()?></td>
	<td><?php echo $att->email();?></td>
	<td><?php echo $att->adresse();?></td>
	<td><?php echo $att->code_postal();?></td>
	<td><?php echo $att->ville();?></td>
	<td><?php echo $att->departement();?></td>
	<td><?php echo $att->telephone();?></td>
	
	<td><a href='post.php?what=client&where=<?php echo $att->id_c(); ?>&action=modifier'>modifier</a> | <a href='post.php?what=client&action=supprimer&where=<?php echo $att->id_c();?>'>supprimer</a></td>
</tr>
<?php }?>
<?php endif;?>
</table>

<p><?php if(isset($formAnswer)) echo $formAnswer;?></p>



<?php $view_section= ob_get_clean();?>