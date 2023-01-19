<link rel="stylesheet" type='text/css' href="<?=base_url()?>assets/validate/validate.css"/>
<script type="text/javascript" src="<?=base_url()?>assets/validate/jquery.validate.min.js"></script>
<style type="text/css">
table.historico tr th, table.historico tr td {border: 1px solid #c3c9d4;}
</style>
<script type="text/javascript">
$(document).ready(function() {

});
</script>
<div class="container" style="width: 900px; margin: 0px;">
	<div class="box" style="margin: 0px;">
	<div class="head">
		<h2>
		<span class="icon en">&</span>
		<span class="title">Historial</span>
		</h2>
	</div>
	<div class="content">
    <div id="message" style="display: none;"></div>
	<table class="data_table historico">
        <thead>
        <th>Trayecto</th>
        <th style="width: 150px;">Paso</th>
        <th style="width: 150px;">Usuario</th>
        <th style="width: 200px;">Fecha</th>
        <th style="width: 100px;">Estado</th>
        <th style="width: 200px;">Observaciones</th>
        </thead>
        <tbody>
        <?php foreach($data as $row){ ?>
        <tr>
            <td><?php echo $row->try_id; ?></td>
            <td><?php echo $row->paso; ?></td>
            <td><?php echo $row->username; ?></td>
            <td><?php echo $row->fecha; ?></td>
            <td><?php echo $row->estado; ?></td>
            <td><?php echo $row->obs; ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
	</div>
	</div>
</div>