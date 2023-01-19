<!DOCTYPE html>
<html>

<head>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.library.1.7.2.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery-ui.1.10.3.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title><?php echo $this->config->item('sistema');?> | <?php echo $titulo?></title>

	<link rel="shortcut icon" href="<?php echo base_url();?>media/imagenes/sistema/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php echo base_url();?>media/imagenes/sistema/apple-touch-icon.png" />

	<!--Stylesheets-->
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/style.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/colors.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/jquery-ui.1.10.3.css" />
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/modals.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/datatable.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/select.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/checkboxes.css"/>

	<!--adecuacion bootstrap css-->
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>css/bootstrap.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>css/bootstrap-theme.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>css/bootstrap.css.map"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>css/menu.css"/>

	<!--adecuacion bootstrap js-->


	<script type="text/javascript" src="<?php echo base_url();?>js_boot/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js_boot/menu.js"></script>



	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/editor.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/fullcalendar.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/notifications.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/progress.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/slider.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/static.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/tags.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/tooltips.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/botones.css"/>
	<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>assets/validate/validate.css"/>

	<!--Scripts-->

	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.ui.library.custom.1.8.21.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.accordion.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.tabbed.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.placeholder.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.fancybox.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/application.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.checkboxes.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.datatables.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/grocery_crud/js/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-es.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.happy.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.notify.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.select2.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.slider.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.tags.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.fullcalendar.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.tipsy.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/FUload.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/masked.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.mask.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/validate/jquery.validate.min.js"></script>



	<!-- Utilidades JS -->	
	<script src="<?php echo base_url('js/config/global.js')?>"></script>
	<script src="<?php echo base_url('js/config/gallery.js')?>"></script>
	<script src="<?php echo base_url('js/sistema/utils.js');?>"></script>

<!--[if lt IE 9]>
	<script src="scripts/css3-mediaqueries.js"></script>
	<![endif]-->

<!--[if lt IE 9]>
	<script language="javascript" type="text/javascript" src="scripts/excanvas.js"></script>
	<![endif]-->

	<script type="text/javascript">
		$(document).ready(function(){
 //$("form").on("keyup", "input[type='text']", function () { $(this).val($(this).val().toUpperCase()); } );

 
 tipo_alerta='<?php echo $this->session->flashdata('tipo_alerta');?>';
 if(tipo_alerta!="")
 	create(tipo_alerta, {  
 		title:'<?php echo $this->session->flashdata("titulo_alerta");?>',
 		text:'<?php echo $this->session->flashdata("texto_alerta");?>'
 	}, { expires: false
 	});
});


		$('.agregar_elemento_catalogo').live({
			click: function(event) {
				event.preventDefault();
				tabla = $(this).attr('tabla');
				contenedor = $(this).attr('contenedor');
				nombre_actual = $(this).attr('nombreactual');
				id_actual = $(this).attr('idactual');
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>'+'uatm/soluciones/generar_nuevo_elemento_catalogo',
					data: {tabla:tabla, contenedor:contenedor, nombre_actual:nombre_actual, id_actual:id_actual},
					success: function(data){
						$.fancybox(data)
					}
				});
			}
		});




	</script>

</head>

<body>

	<div class="navbar navbar-default navbar-fixed-top" role="navigation">

		<div class="container">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Planillas</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="left_item">
						<a href='<?php echo base_url()?>'>
							<!--<span class="icon en">m</span>-->
							<span class="title">Inicio</span>
						</a>			
					</li>	
					<?php 


					foreach ($menu0 as $m0) 
					{
						?>
						<li class="left_item">
							<a href='<?php echo base_url().$m0['opc_funcion'].'/index'?>'>
								<!--<span class="icon en"><?php echo $m0['opc_icono']?></span>-->
								<span class="title"><?php echo $m0['opc_nombre']?></span>
							</a>			
						</li>			
						<?php
					}
					?>


				</ul>
				<ul class="nav navbar-nav navbar-right">

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="title"><?php echo $username; ?></span>
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo base_url().'auth/change_password'; ?>">
										<span class="title">Cambiar contraseña</span>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url().'auth/logout'; ?>">
										<span class="title">Cerrar Sesion</span>
									</a>
								</li>

							</ul>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>



		<div id="wrapper">
			<?php if(count($menu1)>0){?>

			<div id="sidebar">
				<div id="sidebar_toggle"></div>
				<div id="sidebar_menu">			
					<?php

					foreach ($menu1 as $m1)
					{
						$total=0;
						$hijos=array();
						if($m1['opc_hijo']==1)
						{
							$hijo=" add_menu";
							foreach ($menu2 as $m2) 
							{
								if($m2['opc_padre']==$m1['opc_id'])
								{
									$total++;
									$hijos[]=array('opc_nombre'=>$m2['opc_nombre'],'opc_funcion'=>$m2['opc_funcion'],'opc_descripcion'=>$m2['opc_descripcion']);						
								}
							}
						}
						else
							$hijo="";
						?>



						<div class="col-md-3" id="leftCol">

							<div class="well"> 
								<ul class="nav nav-stacked" id="sidebar">
									<li>
										<a href="<?php echo base_url().$modulo.'/'.$m1['opc_funcion']?>" title="<?php echo $m1['opc_descripcion']?>">					
									&nbsp;<?php echo $m1['opc_nombre']?>
									</a>
									</li>
								</ul>
							</div>

							</div>
							<?php
							if($m1['opc_hijo']==1)
							{
								?>
								<ul class="sub_menu">
									<?php

									?>
								</ul>
								<?php
							}
						}
						?>


					</div>
				</div>
				<div id="main_content">
					<?php } ?>

					<div class="container">

						<div class="box">
							<div class="head">
								<h2>						
									<span class="title"><?php print_r($descripcion)?></span>
								</h2>
							</div>
							<div class="content">
								<!-- MasterPage tags must be capitalized and rest lowercase. -->
								<mp:Content />
								<div style="text-align:center; clear:both;" >
									<a href="" target="blank">Powered by Warsoft</a>
								</div>
							</div>
						</div>


					</div>

				</div>

				<div id="notifications">
					<div id="note">		
						<a class="ui-notify-close" href="#">X</a>		
						<h4>#{title}</h4>
						<p>#{text}</p>
					</div>
					<div id="note_success" class="success icon">
						<a class="ui-notify-close" href="#">X</a>
						<div class="icon_box"><span class="icon ws">/</span></div>
						<h4>#{title}</h4>
						<p>#{text}</p>
					</div>
					<div id="note_warning" class="warning icon">
						<a class="ui-notify-close" href="#">X</a>
						<div class="icon_box"><span class="icon ws">W</span></div>
						<h4>#{title}</h4>
						<p>#{text}</p>
					</div>
					<div id="note_error" class="error icon">
						<a class="ui-notify-close" href="#">X</a>
						<div class="icon_box"><span class="icon ws">'</span></div>
						<h4>#{title}</h4>
						<p>#{text}</p>
					</div>
				</div>

			</body>
			</html>