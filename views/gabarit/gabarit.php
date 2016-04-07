<!DOCTYPE html/>
<html>
	<head>
		<meta charset="utf-8"/>
		
		<link href="reset.css" rel="stylesheet" type="text/css"/>
		<link href="style.css" rel="stylesheet" type="text/css"/>
		<title><?php //echo $page->titre();?></title>
		<meta name="Content-Language" content="fr">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="Description" content="<?php //echo $page->description();?>">
		<meta name="Keywords" lang='fr' content="<?php //echo $page->keywords();?>">
		<meta name="Subject" content="<?php //echo $page->subject();?>">
	</head>
	<body>
		<div id='wrap'>
			<div id='wrap_leftNav'>
			<?php echo $view_leftNav;?>
			</div><!--
		--><div id='wrap_section'><!--
			--><h2><?php echo $page;?></h2>
			<?php if(isset($view_section)) echo $view_section;?>
			<?php if(isset($view_panier)) echo $view_panier;?>
			<?php if(isset($view_boxAlert)) echo $view_boxAlert;?>
			<?php if(isset($view_boxValidCommand)) echo $view_boxValidCommand;?>			<?php // NE POURRA PAS RESTER LA!!!?>
			</div><!--
	--></div>
	</body>
	<!--<script src='fonctions.js' type='text/javascript'></script>-->
</html>