<?php ob_start();?>

<form id= 'form_command' action=<?php echo $form_action;?> method='POST'>

Mode de livraison : 
<input type='radio' name='mode_liv' value='livraison' checked/> Livraison à domicile (40% du panier : <?php echo $panier->prevTarifLiv('livraison');?>)<br/>
<input type='radio' name='mode_liv' value='retrait'/> Retrait à la ferme (20% du panier : <?php echo $panier->prevTarifLiv('retrait');?>)<br/>
Mode de paiement :
<input type='radio' name='mode_paiement' value='Espèce' checked/> Espèce<br/>
<input type='radio' name='mode_paiement' value='En ligne'/> Carte bleu (paiement en ligne)<br/>

<textarea name='commentaire'>Informations complémentaire pour la livraison</textarea><br/>
<a href='commander.php?step=Annuler'>Annuler</a>
<input type='submit' name='Enregistrer' value='Enregistrer'/>

</form>


<?php $view_section = ob_get_clean();?>