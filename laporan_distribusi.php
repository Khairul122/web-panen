<?php
require_once 'tcpdf/tcpdf.php';
include 'koneksi.php';

class DistributionReportGenerator {
    private $conn;
    private $pdf;

    private $bulanArray = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    public function __construct($conn) {
        $this->conn = $conn;
        $this->initializePDF();
    }

    private function initializePDF() {
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('UD Galang');
        $this->pdf->SetTitle('Laporan Distribusi');
        $this->pdf->SetSubject('Laporan Distribusi');
        $this->pdf->SetKeywords('Laporan, Distribusi, PDF');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
    }

    private function namaBulan($bulanAngka) {
        return $this->bulanArray[$bulanAngka] ?? '';
    }

    private function generatePeriodeText($tanggal, $bulan, $tahun) {
        if ($tanggal) {
            $tanggalFormatted = date('d', strtotime($tanggal));
            $bulanFormatted = $this->namaBulan(date('m', strtotime($tanggal)));
            $tahunFormatted = date('Y', strtotime($tanggal));
            return "$tanggalFormatted $bulanFormatted $tahunFormatted";
        } elseif ($bulan && $tahun) {
            $bulanFormatted = $this->namaBulan(str_pad($bulan, 2, '0', STR_PAD_LEFT));
            return "$bulanFormatted $tahun";
        } elseif ($tahun) {
            return $tahun;
        }
        return '';
    }

    public function generateReport($filters) {
        $tanggal = $filters['tanggal'] ?? null;
        $bulan = $filters['bulan'] ?? null;
        $tahun = $filters['tahun'] ?? null;
        $peron = $filters['peron'] ?? null;

        $data = $this->fetchData($filters);
        $this->pdf->AddPage();
        $this->generateHeader();
        $this->generateTitle($this->generatePeriodeText($tanggal, $bulan, $tahun), $peron);
        $this->generateTable($data);
        $this->generateFooter();

        $this->pdf->Output('Laporan_Distribusi.pdf', 'I');
    }

    private function fetchData($filters) {
        $query = "
            SELECT distribusi.*, user.peron, user.name
            FROM distribusi
            JOIN user ON distribusi.create_by = user.id_user
            WHERE 1=1";
        $params = [];

        if ($filters['tanggal']) {
            $query .= " AND DATE(tanggal_distribusi) = ?";
            $params[] = $filters['tanggal'];
        }
        if ($filters['bulan']) {
            $query .= " AND MONTH(tanggal_distribusi) = ?";
            $params[] = $filters['bulan'];
        }
        if ($filters['tahun']) {
            $query .= " AND YEAR(tanggal_distribusi) = ?";
            $params[] = $filters['tahun'];
        }
        if ($filters['peron']) {
            $query .= " AND user.peron = ?";
            $params[] = $filters['peron'];
        }

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
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

    private function generateTitle($periode, $peron) {
        $this->pdf->SetFont('times', 'B', 16);
        $this->pdf->Cell(0, 10, 'Laporan Distribusi', 0, 1, 'C');
        $this->pdf->SetFont('times', '', 12);

        if ($periode) {
            $this->pdf->Cell(0, 10, "Periode: $periode", 0, 1, 'C');
        }
        $this->pdf->Ln(5);
    }

    private function generateTable($result) {
        $this->pdf->SetFont('times', '', 10);

        $html = '<table border="1" cellpadding="5">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>No</th>
                    <th>Tanggal Distribusi</th>
                    <th>Peron</th>
                    <th>Jumlah Distribusi</th>
                    <th>No Kendaraan</th>
                    <th>Supir</th>
                    <th>Status Pengiriman</th>
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
                    htmlspecialchars($row['tanggal_distribusi']),
                    htmlspecialchars($row['peron']),
                    htmlspecialchars($row['jumlah_distribusi']),
                    htmlspecialchars($row['no_kendaraan']),
                    htmlspecialchars($row['supir']),
                    htmlspecialchars($row['status_pengiriman']),
                    htmlspecialchars($row['name'])
                );
            }
        } else {
            $html .= '<tr><td colspan="7" align="center">Tidak ada data</td></tr>';
        }

        $html .= '</tbody></table>';
        $this->pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generateFooter() {
        $currentDate = date('d') . ' ' . $this->bulanArray[date('m')] . ' ' . date('Y');

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

$filters = [
    'tanggal' => $_GET['tanggal_distribusi'] ?? null,
    'bulan' => $_GET['bulan_distribusi'] ?? null,
    'tahun' => $_GET['tahun_distribusi'] ?? null,
    'peron' => $_GET['peron'] ?? null
];

$reportGenerator = new DistributionReportGenerator($conn);
$reportGenerator->generateReport($filters);
?>
