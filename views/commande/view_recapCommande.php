<?php 
// VIEW- recapCommande : Affiche un récapiulatif de la commande ( Liste des articles du panier/ Parametres de livraison et paiement / Montant total.)
?>

<?php ob_start();?>

<table id='boxRecapCommande'>
	<tr>
		<th>Produit</th>
		<th>Prix unitaire HT</th>
		<th>TVA</th>
		<th>Quantite</th>
		<th>Prix TTC</th>
	</tr>
	<?php foreach($panier->list_lc() as $lc):?>
	<tr>
		<td><?php echo $lc->titre();?></td>
		<td><?php echo $lc->prix_HT();?></td>
		<td><?php echo $lc->tva();?></td>
		<td><?php echo $lc->quantite();?></td>
		<td><?php echo $lc->valeur();?></td>
	</tr>
	<?php endforeach;?>
</table>
<table id='boxRecapParam'>
	<tr>
		<th>Mode de livraison</th>
		<th>Tarif livraison</th>
	</tr>
	<tr>
		<td><?php echo $commande->mode_liv();?></td>
		<td><?php echo $commande->tarifLiv();?> €</td>
	</tr>
</table>
<div id='boxTotal'>
	Total à payer : <?php echo $commande->calculTotal();?> €
</div>
<div id='boxRecapModePaiement'>
	Mode de règlement : <?php echo $commande->mode_paiement();?>
</div>
<div id='buttonValidCommand'>
	<a href='commander.php?step=Validation&action=ajouter&Confirmer'>Valider la commande</a>
</div>

<?php $view_recapCommande = ob_get_clean();?>