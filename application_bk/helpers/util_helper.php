
<?php

function ver($array){
echo "<pre>";
print_r($array);
echo "</pre>";

}
//1)array con los datos necesarios
//2) titulo que se desplegara en el drop
//3) valor de cada opcion del dropdown
//$tipo de drop idetica si sera drop multple o sencillo
//$arrelo d svn 
function get_dropdown($name, $arreglo,$titulo,$value,$texto_inicio,$tipo_drop=null,$arreglo_s_n=null){
if($texto_inicio != ""){
array_unshift($arreglo, array($value=>0, $titulo=>$texto_inicio));	
}

if($tipo_drop==2){
	if($arreglo_s_n==1){
$drop="<select multiple id=".$name." name=".$name."[] style='width:100%; height:52px;'>";
   }else{
$drop="<select multiple id=".$name." name=".$name." style='width:100%; height:52px;'>";

   }
}else{
if($arreglo_s_n!=""){
$drop="<select multiple id=".$name." name=".$name."[] style='width:100%; height:52px;'>";
}else{
$drop="<select  id=".$name." name=".$name." style='width:100%; height:42px;'>";
}
}
foreach($arreglo as $arr){

$drop.="<option value=".$arr[$value].">".$arr[$titulo]."</option>";

                         }
$drop.= "</select>";
return $drop; 

   }


function f_graf($array=null,$titulo=null){

?>	
<script>
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '<?php echo $titulo; ?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: ' Campañas',
                data: [
                <?php
                $cuantos_datos=count($array);
                $contador_datos=0;
                foreach($array as $valores)
                {
                    $contador_datos++;
                                        
                    echo "['".$valores['dato']."' , ".$valores['total']."]";
                    
                    if($cuantos_datos != $contador_datos)
                    {
                        
                        echo ",";
                    }
                    
                } 
                
                ?>
                   /* ['Firefox',   45.0],
                    ['IE',       26.8],
                    ['Safari',    8.5],
                    ['Opera',     6.2],
                    ['Others',   0.7]*/
                ]
            }]
        });
    });
    
});
</script>
<?php 
}





?>
