<?php ob_start();?>

<div id='boxAlert'>
	Vous aviez un panier "en cours" : 

<form id='form_boxAlert' action='' method='POST'>

	Fusionner les paniers<input type='checkbox' value='fusionner' name='ChoixUser'/>
	Ecraser mon ancien panier<input type='checkbox' value='ecraser' name='ChoixUser'/>
	Conserver mon ancien panier<input type='checkbox' value='conserver' name='ChoixUser'/>
	
<input type='submit' name='Sélectionner'/>

</div>

<?php $view_boxAlert	=	ob_get_clean();?>

	