<?php ob_start();?>

<div id='boxDetailPanier'>
<div id='boutonPanier'></div><div id='bouton_viderPanier'><a href='boutique.php?vider'></a></div>
<ul id='boxPanier_th'>
	<li class='boxPanier_thProduit'><strong>Produit</strong></li><li class='boxPanier_thPU'><strong>P.U</strong></li><li class='boxPanier_thQte'><strong>Qté</strong><li class='boxPanier_thTotal'><strong>Total</strong></li>
</ul>
<ul id='ul_boxPanier'>
	<?php if(!empty($panier->list_lc())):?>
	<?php foreach ($panier->list_lc() as $lc):?>
	<li class='li_boxPanier'>
		<ul id='boxPanier_tr'>
			<li class='boxPanier_thProduit'><strong><img id='miniImg' src='<?php echo $lc->img();?>'/><?php echo $lc->titre();?></<strong></li><li class='boxPanier_thPU'><strong><?php echo $lc->prix_vente();?></strong></li><li class='boxPanier_thQte'><strong><?php echo $lc->quantite();?></<strong></li><li class='boxPanier_thTotal'><strong><?php echo $lc->valeur();?>€</strong></li>
		</ul>
	</li>
	<?php endforeach;?>
	<?php else:?>
	Panier vide
	<?php endif;?>
</ul>
<div id='boxPanier_total'>Total : <?php echo $panier->valeur();?>€</div>
</div>

<?php $view_panier = ob_get_clean();?>