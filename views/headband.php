<?php ob_start();?>

<div id='headband'>
	<ul id='ul_headband'>
		<li><a href='admin.php?what=produit&show=list'>Produits</a></li>
		<li><a href='admin.php?what=categorie&show=list'>Categories</a></li>
		<li><a href='admin.php?what=producteur&show=list'>Producteurs</a></li>
		<li><a href='admin.php?what=client&show=list'>Clients</a></li>
		<li><a href='admin.php?what=panier&show=list'>Paniers</a></li>
		<li><a href='admin.php?what=commande&show=list'>Commandes</a></li>
		<li><a href='admin.php?what=adhesion&show=list'>Adhesions</a></li>
	</ul>
</div>
<?php $view_headBand = ob_get_clean();?>