<?php

// View_formConnexionAuthentification... Vue du formulaire étape "authentification";
// Contient le formulaire d'inscription et le formulaire d'authentification.

?>

<?php ob_start();?>

<div id='box_formInscription'><?php echo $view_formInscription;?></div>
<div id='box_formConnexion'><?php echo $view_formConnexion;?></div>


<?php $view_formCommandeAuthentification	=	ob_get_clean();?>