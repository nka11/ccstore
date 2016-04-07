<?php ob_start();?>

<form id= 'form_command' action=<?php echo $form_action;?> method='POST'>

<h3>Choisir un mode de livraison : </h3>
<div class='radio_formParam'>
<input type='radio' name='mode_liv' value='livraison' checked/><p>Livraison à domicile (40% du panier : <?php echo $panier->prevTarifLiv('livraison');?>)</p>
</div><div class='formParam'>
<input type='radio' name='mode_liv' value='retrait'/><p>Retrait à la ferme (20% du panier : <?php echo $panier->prevTarifLiv('retrait');?>)</p>
</div>
<h3>Choisir un mode de règlement : </h3>
<input type='radio' name='mode_paiement' value='Espèce' checked/> Espèce<br/>
<input type='radio' name='mode_paiement' value='En ligne'/> Carte bleu (paiement en ligne)<br/>

<textarea name='commentaire'>Informations complémentaire pour la livraison</textarea><br/>
<a href='commander.php?step=Annuler'>Annuler</a>
<input id='submit_formParam' type='submit' name='Enregistrer' value=''/>

</form>


<?php $view_section = ob_get_clean();?>