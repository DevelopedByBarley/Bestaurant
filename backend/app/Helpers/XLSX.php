<?php

namespace App\Helpers;

class XLSX
{
  private $Pdo;
  private $Alert;

  public function __construct()
  {
  }

  public function write($data)
  {
    // Ellenőrzés, hogy az adatok üresek-e vagy nem tömb típusúak
    if (empty($data) || !is_array($data)) {
      echo 'Something went wrong';
      return; // Visszatérünk a hiba esetén
    }

    // Létrehozunk egy új munkalapot
    $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $excel->getActiveSheet();

    // Fejlécek definiálása
    $headers = [
      "id",
      "name",
      "email",
      "pw",
      "create_at"
    ];

    // Beírjuk a fejléceket
    $columnIndex = 1;
    foreach ($headers as $header) {
      $sheet->setCellValueByColumnAndRow($columnIndex, 1, $header);
      $columnIndex++;
    }

    // Adatok beírása
    $rowIndex = 2; // A második sorról kezdünk
    foreach ($data as $rowData) {
      // A rekordon belüli mezők beírása
      $columnIndex = 1;
      foreach ($rowData as $value) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
        $columnIndex++;
      }
      $rowIndex++;
    }

    // Exportálás
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="export_data_' . time() . '.xlsx"');
    header('Cache-Control: max-age=0');

    $xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
    $xlsxWriter->save('php://output');
    exit;
  }
}
