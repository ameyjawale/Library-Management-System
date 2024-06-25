<?php
// Start session and establish database connection
session_start();
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");

// Fetch issued book details based on user session
$query = "SELECT book_name, book_author, book_no FROM issued_books WHERE student_id = {$_SESSION['id']} AND status = 1";
$query_run = mysqli_query($connection, $query);

// Include FPDF library for PDF generation
require('fpdf/fpdf.php');

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set font for PDF
$pdf->SetFont('Arial', '', 12);

// Add a title
$pdf->Cell(0, 10, 'Library Management System - Issued Book Receipt', 0, 1, 'C');

// Add user information
$pdf->Cell(0, 10, 'Student Name: ' . $_SESSION['name'], 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $_SESSION['email'], 0, 1);
$pdf->Ln(10);

// Set table headers
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Book Name', 1, 0, 'C');
$pdf->Cell(60, 10, 'Author', 1, 0, 'C');
$pdf->Cell(60, 10, 'Book Number', 1, 1, 'C');

// Display issued book details in a table format
$pdf->SetFont('Arial', '', 12);
while ($row = mysqli_fetch_assoc($query_run)) {
    $pdf->Cell(60, 10, $row['book_name'], 1, 0, 'L');
    $pdf->Cell(60, 10, $row['book_author'], 1, 0, 'L');
    $pdf->Cell(60, 10, $row['book_no'], 1, 1, 'C');
}

// Output the PDF as a download
$pdf->Output('D', 'issued_books_receipt.pdf');
