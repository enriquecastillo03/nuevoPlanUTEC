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
<script type="text/javascript">
$(document).ready(function(){
    $(".pass_new").click(function(event){
        event.preventDefault();
        user = $(this).attr('href');
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>sistema/auth/forgot_password",
            data: {
                login: user
            }
        }).done(function(html){
            create("note_success", {
                title:'Se envio notificación',
                text:'Se ha enviado un correo electrónico con las instrucciones para crear una nueva contraseña.'
                },
                { expires: 5000}
            );
        });
    })
});
</script>
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>