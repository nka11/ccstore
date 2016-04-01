<?php ob_start();?>

<div id='boxAlert'>
	Vous aviez un panier "en cours" : 

<form id='form_boxAlert' action='boutique.php?exec_boxAlert' method='POST'>

	<input type='checkbox' value='fusionner' name='ChoixUser'/>Fusionner les paniers
	<input type='checkbox' value='ecraser' name='ChoixUser'/>Ecraser mon ancien panier
	<input type='checkbox' value='conserver' name='ChoixUser'/>Conserver mon ancien panier
	
<input type='submit' value='Confirmer' name='Selectionner'/>

</div>

<?php $view_boxAlert	=	ob_get_clean();?>

	