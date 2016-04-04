<?php ob_start();?>

<div id='boxDetailPanier'>
<div id='boutonPanier'></div><div id='bouton_viderPanier'><a href='boutique.php?vider'></a></div>

<ul id='ul_boxPanier'>
	<?php if(!empty($panier->list_lc())):?>
	<?php foreach ($panier->list_lc() as $lc):?>
	<li class='li_boxPanier'><b><?php echo $lc->quantite();?> x </b><img id='miniImg' src='<?php echo $lc->img();?>'/><?php echo $lc->titre();?><b><?php echo $lc->valeur();?>€</li>
	<?php endforeach;?>
	<?php else:?>
	Panier vide
	<?php endif;?>
</ul>
<div id=''>Total : <?php echo $panier->valeur();?>€</div>
</div>

<?php $view_panier = ob_get_clean();?>