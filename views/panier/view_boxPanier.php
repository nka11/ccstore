<?php ob_start();?>
	
	<div id='boxPanier'>
	<img id='imgPanier' src='img/design/boutonPanier.png'/><div id='bouton_viderPanier'><a href='boutique.php?vider'></a></div>
	<div id='countLc'><?php echo count($panier->list_lc());?> articles</div>
	<div id='valuePanier'><?php echo $panier->valeur();?>€</div>
	</div>
	
<?php $view_boxPanier	=	ob_get_clean();?>

	