<?php ob_start();?>
<form id='connexion' action="admin.php?show=list&what=produit" method="post">
			
				<input id='login' type="text" name="login" placeholder='login' maxlength="30" /><br/>
				<input id='mdp' type="password" name="mdp" placeholder='mot de passe'/><br/><br/>
				<input type="submit" value="connexion" name="connexion_admin" />
<?php	if (isset($formAnswer))
		{?>
			<p>Une erreur s'est produite : </p>
			<p><?php echo $formAnswer;?></p>
<?php	}?>
	</form>
<?php $view_section = ob_get_clean();?>