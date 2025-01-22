<?php
require_once 'tcpdf/tcpdf.php';
include 'koneksi.php';

class HarvestReportGenerator {
    private $conn;
    private $pdf;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->initializePDF();
    }

    private function initializePDF() {
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('UD Galang');
        $this->pdf->SetTitle('Laporan Hasil Panen');
        $this->pdf->SetSubject('Laporan Hasil Panen');
        $this->pdf->SetKeywords('Laporan, Hasil Panen, PDF');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
    }

    public function generateReport() {
        $data = $this->fetchData();

        $this->pdf->AddPage();
        $this->generateHeader();
        $this->generateTitle();
        $this->generateTable($data);
        $this->generateFooter();

        $this->pdf->Output('Laporan_Hasil_Panen.pdf', 'I');
    }

    private function fetchData() {
        $query = "SELECT panen.*, user.name, peron.nama_peron
                  FROM panen
                  JOIN user ON panen.create_by = user.id_user
                  JOIN peron ON panen.id_peron = peron.id_peron";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error preparing statement: " . $this->conn->error);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    private function generateHeader() {
        $this->pdf->SetFont('times', 'B', 24);
        $this->pdf->Cell(0, 10, 'UD GALANG', 0, 1, 'C');
        $this->pdf->SetFont('times', '', 12);
        $this->pdf->Cell(0, 8, 'Jln. Lintas Sungai Jariang, Kec. Lubuk Basung, Kab Agam, Sumatera Barat', 0, 1, 'C');
        $this->pdf->Ln(5);
        $this->pdf->Line(15, $this->pdf->GetY(), 195, $this->pdf->GetY());
        $this->pdf->Ln(10);
    }

    private function generateTitle() {
        $this->pdf->SetFont('times', 'B', 16);
        $this->pdf->Cell(0, 10, 'Laporan Hasil Panen', 0, 1, 'C');
        $this->pdf->Ln(5);
    }

    private function generateTable($result) {
        $this->pdf->SetFont('times', '', 10);

        $html = '<table border="1" cellpadding="5">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>No</th>
                    <th>Tanggal Panen</th>
                    <th>Nama Peron</th>
                    <th>Kebun</th>
                    <th>Berat Hasil (Kg)</th>
                    <th>Jumlah Tandan</th>
                    <th>Catatan</th>
                    <th>Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>';

        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                $html .= sprintf(
                    '<tr>
                        <td>%d</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                    </tr>',
                    $no++,
                    htmlspecialchars($row['tanggal_panen']),
                    htmlspecialchars($row['nama_peron']),
                    htmlspecialchars($row['kebun']),
                    htmlspecialchars($row['berat_hasil']),
                    htmlspecialchars($row['jumlah_tandan']),
                    htmlspecialchars($row['catatan']),
                    htmlspecialchars($row['name'])
                );
            }
        } else {
            $html .= '<tr><td colspan="8" align="center">Tidak ada data</td></tr>';
        }

        $html .= '</tbody></table>';
        $this->pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generateFooter() {
        $currentDate = date('d') . ' ' . date('F') . ' ' . date('Y');

        $this->pdf->Ln(10);
        $this->pdf->SetX(150);
        $this->pdf->Cell(0, 6, "Lubuk Basung, $currentDate", 0, 1, 'L');
        $this->pdf->SetX(150);
        $this->pdf->Cell(0, 6, 'Mengetahui,', 0, 1, 'L');
        $this->pdf->Ln(20);
        $this->pdf->SetX(150);
        $this->pdf->Cell(0, 10, 'Pimpinan UD Galang', 0, 1, 'L');
    }
}

$reportGenerator = new HarvestReportGenerator($conn);
$reportGenerator->generateReport();
