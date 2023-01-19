<html>
     <head>
      <style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:30px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
             <title>
                 View your Excel Filess in a Web Page. | Visualiza tu Archivo Excel en una Pagina Web
             </title>
     </head>
     <body>



                <?php 
                 echo '<table border=1>' . "\n";

foreach ($objWorksheet->getRowIterator() as $row)
{
  echo '<tr>' . "\n";
  $cellIterator = $row->getCellIterator();
  $cellIterator->setIterateOnlyExistingCells(false);

  foreach ($cellIterator as $cell)
  {
    echo '<td>' . $cell->getValue() . '</td>' . "\n";
  }

  echo '</tr>' . "\n";
}

echo '</table>' . "\n";

?>
         </body>
</html>