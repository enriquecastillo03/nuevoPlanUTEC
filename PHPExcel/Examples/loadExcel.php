error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');
set_include_path(get_include_path() . PATH_SEPARATOR . './Classes/');

include 'PHPExcel/IOFactory.php';

$fileType = 'Excel5';
$fileName = '01simple.xls';

// Read the file
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($fileName);

// Change the file
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B1', 'World!');

// Write the file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save($fileName);		