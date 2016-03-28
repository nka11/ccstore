<form id='form_lc' action='boutique.php?' method='POST'>

	<input id='quantite'	type='number'	name='quantite' min='1'/>
	<input id='id_p'		type='hidden'	name='id_p'  value='<?php echo $pro->id_p();?>'/>
	<input id='action'		type='hidden'	name='action'	value='ajouter'/>
	<input id='submitToPanier'		type='submit' 	name='submitToPanier' value='Ajouter au panier'/>
	
</form>