<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/Project/princespark/core/init.php';

$sql = "SELECT * FROM transaction ORDER BY id";
$tresult = $db->query($sql);

require("library/fpdf.php");

$pdf = new FPDF('p', 'mm', 'A4');

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);

$pdf->cell(40, 5, "", 0, 0, '');
$pdf->cell(100, 5, "Princess park Online Food Order Transactions", 0, 1, 'C');
$pdf->cell(100, 5, "", 0, 1, '');

$pdf->SetFont('Arial', 'B', 8);

$pdf->cell(10, 5, "Bill ID", 1, 0, 'C');
$pdf->cell(40, 5, "Name", 1, 0, 'C');
$pdf->cell(50, 5, "Email", 1, 0, 'C');
$pdf->cell(18, 5, "Sub. To.", 1, 0, 'C');
$pdf->cell(18, 5, "Del. Co.", 1, 0, 'C');
$pdf->cell(18, 5, "Grnd. To.", 1, 0, 'C');
$pdf->cell(40, 5, "Trans. Date", 1, 1, 'C');


$pdf->SetFont('Arial', '', 8);


while($row = mysqli_fetch_assoc($tresult)){
  $pdf->cell(10, 5, $row['cart_id'], 1, 0, 'C');
  $pdf->cell(40, 5, $row['full_name'], 1, 0, 'C');
  $pdf->cell(50, 5, $row['email'], 1, 0, 'C');
  $pdf->cell(18, 5, money($row['sub_total']), 1, 0, 'C');
  $pdf->cell(18, 5, money($row['d_cost']), 1, 0, 'C');
  $pdf->cell(18, 5, money($row['grand_total']), 1, 0, 'C');
  $pdf->cell(40, 5, $row['txn_date'], 1, 1, 'C');
}

$pdf->OutPut();
 ?>
