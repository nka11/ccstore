<?php ob_start();?>

<form class='form_admin' action=<?php echo $form_action;?> method='POST'>

<input id='nom_c' name='nom_c' placeholder='Nom' type='text' value='<?php if(isset($t)) echo $t->nom();?>'/>
<input id='prenom_c' name='prenom_c' placeholder='Prenom' type='text' value='<?php if(isset($t)) echo $t->prenom();?>'/>
<input id='email_c' name='email_c' placeholder='Email' type='text' value='<?php if(isset($t)) echo $t->email();?>'/>
<input id='adresse_c' name='adresse_c' placeholder='Adresse' type='text' value='<?php if(isset($t)) echo $t->adresse();?>'/>
<input id='cp_c' name='cp_c' placeholder='Code postal' type='text' value='<?php if(isset($t)) echo $t->code_postal();?>'/>
<input id='ville_c' name='ville_c' placeholder='Ville' type='text' value='<?php if(isset($t)) echo $t->ville();?>'/>
<input id='departement_c' name='departement_c' placeholder='Departement' type='text' value='<?php if(isset($t)) echo $t->departement();?>'/>
<input id='telephone_c' name='telephone_c' placeholder='Telephone' type='text' value='<?php if(isset($t)) echo $t->telephone();?>'/>

<?php	if(isset($t)):?>
		<input type='hidden' value='<?php echo $t->id_c();?>' name='id_c'/>
<?php endif;?>
<a href='commander.php?step=annuler'>Annuler</a>
<input type='submit' value='Inscription' name='Inscription'/>

</form>

<?php $view_formInscription = ob_get_clean();?>