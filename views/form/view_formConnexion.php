<?php ob_start();?>
<form id='formConnexion' action=<?php echo $form_action;?> method="post">
			
				<input id='login' type="text" name="login" placeholder='adresse email' maxlength="30" /><!--
			--><input id='mdp' type="password" name="mdp" placeholder='mot de passe'/><!--
			--><input type="submit" value="connexion" name="Connexion" />
<?php	if (isset($formAnswer))
		{?>
			<p>Une erreur s'est produite : </p>
			<p><?php echo $formAnswer;?></p>
<?php	}?>
	</form>
<?php $view_formConnexion = ob_get_clean();?>