<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo config_item('web_title'); ?></title>
		<link href='<?php echo config_item('img'); ?>favicon.png' type='image/x-icon' rel='shortcut icon'>
		<link href="<?php echo config_item('bootstrap'); ?>css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo config_item('bootstrap'); ?>css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="<?php echo config_item('font_awesome'); ?>css/font-awesome.min.css" rel="stylesheet">
		<link href="<?php echo config_item('css'); ?>style-gue.css" rel="stylesheet">
		<link href="<?php echo config_item('css'); ?>jquery.dataTables.min.css" rel="stylesheet">
		<script src="<?php echo config_item('js'); ?>jquery.min.js"></script>
		    <!-- Datatable CSS -->

    <!-- jQuery Library -->

		<script>
		var habiscuy;
		$(document).on({
			ajaxStart: function() { 
				habiscuy = setTimeout(function(){
					$("#LoadingDulu").html("<div id='LoadingContent'><i class='fa fa-spinner fa-spin'></i> Mohon tunggu ....</div>");
					$("#LoadingDulu").show();
				}, 500);
			},
			ajaxStop: function() { 
				clearTimeout(habiscuy);
				$("#LoadingDulu").hide(); 
			}
		});
		</script>
		<style>
			#ModalGue.modal-content{
 width:1000px !important;
 margin-left: 0px !important;

}
		</style>
	</head>
	<body>
		<div id='LoadingDulu'></div>