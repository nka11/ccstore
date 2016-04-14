<?php ob_start();?>

<?php // Ce fichier affiche la variable $listProduit et la propose comme page principale?>

	<?php 	if(count($produits)>0):?>
	<ul id='ul_produit'>
		<?php	foreach($produits as $pro):?>
			<li class='li_produit'>
				<a href="boutiqueTest.php?id=<?php echo $pro->id_p();?>">
					<div class='container_img'><img src='<?php //echo $pro->img();?>'/></div>
					<strong class='titre_produit'><?php echo $pro->titre();?></strong><br/>
					<i><?php //echo $pro->producteurs()->denom();?></i><br/>
					<strong class='prix_vente_produit'><?php echo $pro->prix_vente();?>€</strong>
				</a>
				<div class='form_ligneCom'>
					<?php require 'views/form/view_formLigneCom.php';?>
				</div>
			</li>
		<?php endforeach;?>
	</ul>
	<?php else:?>
	<i>Aucun produit enregistré</i>
	<?php endif;?>

<?php $htmlproduits = ob_get_clean();?>

	