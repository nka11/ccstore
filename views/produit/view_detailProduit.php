<?php ob_start();?>

<?php // Vue section "detail" d'une produit en particulier.
		// Variable du produit : $t (pour target).
?>
<div id='div_detailProduit'>
	<p>Producteur : <?php echo $t->producteur()->denom();?></p>
	<p><?php echo $t->description();?></p>


</div>



<?php $view_section = ob_get_clean();?>