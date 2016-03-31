<?php ob_start();?>

<form id= 'form_command' action=<?php echo $form_action;?> method='POST'>

Mode de livraison : 
<input type='radio' name='mode_liv' value='Livraison à domicile' checked/> Livraison à domicile ( - 10€)<br/>
<input type='radio' name='mode_liv' value='Retrait à la ferme'/> Retrait à la ferme (- 20% du panier)
Mode de paiement :
<input type='radio' name='mode_paiement' value='Espèce' checked/> Espèce<br/>
<input type='radio' name='mode_paiement' value='En ligne'/> Carte bleu (paiement en ligne)

<textarea name='commentaire'>Informations complémentaire pour la livraison</textarea>
<a href='commander.php?step=annuler'>Annuler</a>
<input type='submit' name='Enregistrer' value='Enregistrer'/>

</form>


<?php $view_section = ob_get_clean();?>