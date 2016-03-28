<?php ob_start();?>
<div id='boxValid'>
<a id='<?php echo $divId;?>' href='commander.php?step=<?php echo $session;?>'></a>
</div>
<?php $view_boxValidCommand	=	ob_get_clean();?>