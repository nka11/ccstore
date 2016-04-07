<?php ob_start();?>
<div id='boxValid'>
<a id='<?php echo $divId;?>' href='<?php if ($panier->valeur() >=20) echo 'commander.php?step='.$step;?>'></a>
</div>
<?php $view_boxValidCommand	=	ob_get_clean();?>