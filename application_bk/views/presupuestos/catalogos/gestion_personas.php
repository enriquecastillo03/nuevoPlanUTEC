<meta charset="utf-8" />
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
		<?php if($this->session->flashdata('usuario_editado')==true):
		$datos_cuenta=$this->session->flashdata('usuario_editado');
		//ver($datos_cuenta);

		   ?>
		<script type="text/javascript">
		$(document).ready(function(){
		          function create( template, vars, opts ){
			 	return $notifications.notify("create", template, vars, opts);
			    }

			     $(".note .close").click(function() {
				$(this).closest(".note").animate({"opacity": 0}, 300, function() {
					$(this).slideUp(150);
				});
			});
		         
		        
		$notifications = $("#notifications").notify();

							create("note_success", {
								title:'Usuario editado correctamente',
								text:'' }, {
								expires: false
							});
		 });
							</script>
		<?php endif;?>
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>