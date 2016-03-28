<?php ob_start();?>

<form id= 'form_command' action='commander.php?step=client' method='POST'>

Mode de livraison : 
<input type='radio' name='mode_liv' value='Livraison à domicile' checked/>
<input type='radio' name='mode_liv' value='Retrait à la ferme'/>
Mode de paiement :
<input type='radio' name='mode_liv' value='Livraison à domicile' checked/>
<input type='radio' name='mode_liv' value='Retrait à la ferme'/>

</form>


<?php $view_section = ob_get_clean();?>