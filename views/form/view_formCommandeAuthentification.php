<?php

// View_formConnexionAuthentification... Vue du formulaire étape "authentification";
// Contient le formulaire d'inscription et le formulaire d'authentification.

?>

<?php ob_start();?>

<div id='box_formInscription'>
<div class='text_formAuth'>
	<h3>Première commande?</h3>
<p>Afin de vous livrer dans les meilleurs conditions, merci de remplir le formulaire suivant :</p>
</div>
<?php echo $view_formInscription;?></div><!--
--><div id='box_formConnexion'>
<div class='text_formAuth'>
	<h3>Déjà inscrit?</h3>
<p>Veuillez vous authentifier pour poursuivre votre commande</p>
</div>
<?php echo $view_formConnexion;?></div>
<div id='boutonAnnuler'><a href='commander.php?step=Annuler'>Annuler</a></div>


<?php $view_formCommandeAuthentification	=	ob_get_clean();?>