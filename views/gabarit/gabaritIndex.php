<!DOCTYPE html/>
<html>
	<head>
		<meta charset="utf-8"/>
		
		<link href="reset.css" rel="stylesheet" type="text/css"/>
		<link href="style.css" rel="stylesheet" type="text/css"/>
		<link href='styleIndex.css' rel='stylesheet' type='text/css'/>
		<title><?php //echo $page->titre();?></title>
		<meta name="Content-Language" content="fr">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="Description" content="<?php //echo $page->description();?>">
		<meta name="Keywords" lang='fr' content="<?php //echo $page->keywords();?>">
		<meta name="Subject" content="<?php //echo $page->subject();?>">
	</head>
	<body>
		<div id='wrapIndex'>
			<header>
			<?php echo $view_header;?>
			</header><!--
		--><section>
			<?php if(isset($view_section)) echo $view_section;?>
			</section><!--
		--><footer>
		<?php echo $view_footer;?>
		<?php //echo $view_panier;?>
			</footer>
		</div>
	</body>
	<!--<script src='fonctions.js' type='text/javascript'></script>-->
</html>