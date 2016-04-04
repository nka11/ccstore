<?php ob_start();?>
<form id='formConnexion' action=<?php echo $form_action;?> method="post">
			
				<input class='input_formConnexion' id='login' type="text" name="login" placeholder='adresse email' maxlength="30" /><!--
			--><input class='input_formConnexion' id='mdp' type="password" name="mdp" placeholder='mot de passe'/><!--
			--><input id='boutonConnexion' type="submit" src='' value='' name="Connexion" />
<?php	if (isset($formAnswer))
		{?>
			<p>Une erreur s'est produite : </p>
			<p><?php echo $formAnswer;?></p>
<?php	}?>
	</form>
<?php $view_formConnexion = ob_get_clean();?>