<?php ob_start();?>

<?php // Ce fichier affiche la variable $catList et la propose comme navigateur
?>

<div id='navCat'>

	<?php 	if(!$empty_listCat):?>
	<ul id='ul_navCat'>
	<?php	foreach($listCat as $cat):?>
	<li class='li_navCat'><a href="boutique.php?what=produit&where=<?php echo $cat->tag();?>"><?php echo $cat->tag();?></a></li>
	<?php endforeach;?>
	<?php if($session_admin_open):?>
	<li class='li_navCat'><a href="post.php?what=categorie&action=ajouter">Ajouter une categorie</a></li>
		<?php endif;?>
	</ul>
	<?php else:?>
	<ul id='ul_navCat'>
	<li class='li_navCat'><i>Aucune catégorie enregistrée</i></li>
		<?php if($session_admin_open):?>
	<li class='li_navCat'><a href="post.php?what=categorie&action=ajouter">Ajouter une categorie</a></li>
		<?php endif;?>
	<?php endif;?>
</div>


<?php $view_listCat = ob_get_clean();?>