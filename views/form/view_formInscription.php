<?php ob_start();?>

<form id='formInscription' class='form_admin' action=<?php echo $form_action;?> method='POST'>
<div class='formInsc_innerBox'>
<input class='input_formInsc' id='nom_c' name='nom_c' placeholder='Nom' type='text' value='<?php if(isset($t)) echo $t->nom();?>'/>*
<input class='input_formInsc' id='prenom_c' name='prenom_c' placeholder='Prenom' type='text' value='<?php if(isset($t)) echo $t->prenom();?>'/>*
<input class='input_formInsc' id='email_c' name='email_c' placeholder='Email' type='text' value='<?php if(isset($t)) echo $t->email();?>'/>*
<input class='input_formInsc' id='mdp_c' name='mdp_c' placeholder='Mot de passe' type='password' value='<?php if(isset($t)) echo $t->mdp();?>'/>*
</div><div class='formInsc_innerBox'>
<input class='input_formInsc' id='adresse_c' name='adresse_c' placeholder='Adresse' type='text' value='<?php if(isset($t)) echo $t->adresse();?>'/>*
<input class='input_formInsc' id='cp_c' name='cp_c' placeholder='Code postal' type='text' value='<?php if(isset($t)) echo $t->code_postal();?>'/>*
<input class='input_formInsc' id='ville_c' name='ville_c' placeholder='Ville' type='text' value='<?php if(isset($t)) echo $t->ville();?>'/>*
<input class='input_formInsc' id='departement_c' name='departement_c' placeholder='Departement' type='text' value='<?php if(isset($t)) echo $t->departement();?>'/>
<input class='input_formInsc' id='telephone_c' name='telephone_c' placeholder='Telephone' type='text' value='<?php if(isset($t)) echo $t->telephone();?>'/>
</div>
<?php	if(isset($t)):?>
		<input type='hidden' value='<?php echo $t->id_c();?>' name='id_c'/>
<?php endif;?>
<input type='hidden' value='<?php echo $action;?>' name='action'/>
<input id='boutonInsc' type='submit' value='' name='Inscription'/>

</form>

<?php $view_formInscription = ob_get_clean();?>