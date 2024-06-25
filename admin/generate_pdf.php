<?php
require('./fpdf/fpdf.php');

$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");
$query = "SELECT issued_books.book_name, issued_books.book_author, issued_books.book_no, users.name FROM issued_books LEFT JOIN users ON issued_books.student_id = users.id WHERE issued_books.status = 1";

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set font and size
$pdf->SetFont('Arial', 'B', 12);

// Fetch and output data from the database
$query_run = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($query_run)) {
    $pdf->Cell(0, 10, 'Book Name: ' . $row['book_name'], 0, 1);
    $pdf->Cell(0, 10, 'Author: ' . $row['book_author'], 0, 1);
    $pdf->Cell(0, 10, 'Book Number: ' . $row['book_no'], 0, 1);
    $pdf->Cell(0, 10, 'Student Name: ' . $row['name'], 0, 1);
    $pdf->Ln();
}

// Output the PDF as a download
$pdf->Output('D', 'issued_books_receipt.pdf');
