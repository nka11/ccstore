<?php ob_start();?>

<div id='boxAlert'>
	Vous aviez un panier "en cours" : 

<form id='form_boxAlert' action='boutique.php?exec_boxAlert' method='POST'>

	<input type='radio' value='fusionner' name='ChoixUser'/>Fusionner les paniers
	<input type='radio' value='ecraser' name='ChoixUser'/>Ecraser mon ancien panier
	<input type='radio' value='conserver' name='ChoixUser'/>Conserver mon ancien panier
	
<input type='submit' value='Confirmer' name='Selectionner'/>

</div>

<?php $view_boxAlert	=	ob_get_clean();?>

	