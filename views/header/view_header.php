<?php ob_start();?>
<div id='header_container'>
<div id='header_bandeauTop'>
	<div id='header_logo_cc'>
		<a id='header_a' href='index.php'><img src='img/design/logo/logo_V8.png'/><h1 id='header_titre'>COURT-CIRCUIT</h1></a>
	</div>
	<div id='header_boxUser'><?php echo $view_boxUser;?></div>
</div>
<div id='header_slogan'>Tout le savoir faire local à porté de main</div>
<div id='boxVideo'>
<iframe width="520" height="240" src="https://www.youtube.com/embed/G_aLkr8LE0I" frameborder="0" allowfullscreen></iframe>
</div>
<?php echo $view_mainNav;?>
<?php //echo $view_navCat;?>
</div>
<?php $view_header=ob_get_clean();?>


