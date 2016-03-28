<?php ob_start();?>

<ul id='ul_mainNav'>
<li class='li_mainNav'><a href='boutique.php?show=list'>BOUTIQUE</a></li><!--
--><li class='li_mainNav'>TRAITEUR</li><!--
--><li class='li_mainNav'>CONTACT</li><!--
--><li class='li_mainNav'>ADHESION</li>
</ul>

<?php $view_mainNav = ob_get_clean();?>