<?php ob_start();?>

<span id='emailUser'><?php echo $user->email();?></span><a href=''><img src='img/design/profilButton.png' width='50px;'/></a><a href='index.php?deconnexion'><img id='decoButton' src='img/design/decoButton.png' width='50px'/></a>

<?php $view_boxUser = ob_get_clean();?>