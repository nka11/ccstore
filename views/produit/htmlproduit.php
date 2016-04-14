<?php ob_start();?>

<?php // Affiche un produit en partixulier
		// Travail la variable $produit ( déclaré par la pgae Boutique).
?>
<div id='div_detailProduit'>
	<p>Producteur : <?php echo $produit->producteur()->denom();?></p>
	<p><?php echo $produit->description();?></p>


</div>



<?php $htmlproduit = ob_get_clean();?>