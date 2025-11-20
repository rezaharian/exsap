<?php

namespace App\Http\Controllers\Teknik;

use App\Http\Controllers\Controller;
use App\Models\KerusakanTeknik;
use App\Models\NamaMesin;
use App\Models\Sparepat;
use App\Models\vmacunit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PerbaikanTeknikController extends Controller
{

    // Halaman utama (list data)
    public function index()
    {
        $data = KerusakanTeknik::orderBy('tgl', 'desc')->get();
        return view('teknik.perbaikanteknik.index', compact('data'));
    }

    // Tampilkan form tambah data
    public function create()
    {
        $line = vmacunit::select('LINE')->distinct()->get();
        // dd($line);
        $kodeMesin = NamaMesin::select('kode_mesin')->orderBy('kode_mesin', 'asc')->distinct()->pluck('kode_mesin');
        return view('teknik.perbaikanteknik.create', compact('line', 'kodeMesin'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tgl' => 'required|date',
            'lokasi_line' => 'required|string|max:100',
            'no_mesin' => 'nullable|string|max:50',
            'nama_mesin' => 'nullable|string|max:100',
            'deskripsi_masalah' => 'required|string',
            'tindakan_perbaikan' => 'nullable|string',
            'klasifikasi' => 'nullable|string|max:100',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'durasi_jam' => 'nullable|numeric|min:0',
            'pelaksana' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',

            // validasi untuk sparepart
            'material_sparepart.*' => 'nullable|string|max:255',
            'jumlah.*' => 'nullable|integer|min:0',
        ]);

        // generate kode unik sparepart_cod
        $sparepartCod = 'SP-' . time();

        // simpan ke tabel kerusakan_teknik
        $kerusakan = KerusakanTeknik::create([
            'tgl' => $request->tgl,
            'lokasi_line' => $request->lokasi_line,
            'no_mesin' => $request->no_mesin,
            'nama_mesin' => $request->nama_mesin,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'klasifikasi' => $request->klasifikasi,
            'sparepart_cod' => $sparepartCod,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'durasi_jam' => $request->durasi_jam,
            'pelaksana' => $request->pelaksana,
            'keterangan' => $request->keterangan,
        ]);

        // simpan ke tabel sparepart (bisa lebih dari 1)
        if ($request->has('material_sparepart')) {
            foreach ($request->material_sparepart as $i => $nama) {
                if ($nama) {
                    DB::table('sparepart')->insert([
                        'sparepart_cod' => $sparepartCod,
                        'nama_sparepart' => $nama,
                        'jumlah' => $request->jumlah[$i] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }


        return redirect()->route('kerusakanteknik.index')
            ->with('success', 'Data kerusakan teknik berhasil ditambahkan.');
    }


    // Tampilkan show
    // Tampilkan detail kerusakan
    public function show($id)
    {
        $data = KerusakanTeknik::findOrFail($id);

        // ambil sparepart sesuai sparepat_cod
        $spareparts = DB::table('sparepart')
            ->where('sparepart_cod', $data->sparepart_cod)
            ->get();

        return view('teknik.perbaikanteknik.show', compact('data', 'spareparts'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $line = vmacunit::select('LINE')->distinct()->get();
        $kodeMesin = NamaMesin::select('kode_mesin')->orderBy('kode_mesin', 'asc')->distinct()->pluck('kode_mesin');

        $data = KerusakanTeknik::findOrFail($id);

        // ambil sparepart sesuai sparepat_cod
        $spareparts = DB::table('sparepart')
            ->where('sparepart_cod', $data->sparepart_cod)
            ->get();

        return view('teknik.perbaikanteknik.edit', compact('data', 'line', 'kodeMesin', 'spareparts'));
    }


    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl' => 'required|date',
            'lokasi_line' => 'required|string',
            'no_mesin' => 'nullable|string',
            'nama_mesin' => 'nullable|string',
            'deskripsi_masalah' => 'required|string',
            'tindakan_perbaikan' => 'nullable|string',
            'klasifikasi' => 'nullable|string',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'durasi_jam' => 'nullable|numeric',
            'pelaksana' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // ambil data utama + kode lama
            $data = KerusakanTeknik::findOrFail($id);
            $oldCod = $data->sparepart_cod;

            // buat kode baru
            $sparepartCod = 'SP-' . time();

            // update data utama dengan kode baru
            $data->update([
                'tgl' => $request->tgl,
                'lokasi_line' => $request->lokasi_line,
                'no_mesin' => $request->no_mesin,
                'nama_mesin' => $request->nama_mesin,
                'deskripsi_masalah' => $request->deskripsi_masalah,
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'klasifikasi' => $request->klasifikasi,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'durasi_jam' => $request->durasi_jam,
                'pelaksana' => $request->pelaksana,
                'keterangan' => $request->keterangan,
                'sparepart_cod' => $sparepartCod,
            ]);

            // hapus sparepart lama (pakai kode lama)
            Sparepat::where('sparepart_cod', $oldCod)->delete();

            // insert sparepart baru
            $sparepartNama = $request->sparepart_nama ?? [];
            $sparepartJumlah = $request->sparepart_jumlah ?? [];

            foreach ($sparepartNama as $i => $nama) {
                $jumlah = $sparepartJumlah[$i] ?? 0;

                Sparepat::create([
                    'sparepart_cod' => $sparepartCod,
                    'nama_sparepart' => $nama,
                    'jumlah' => $jumlah,
                ]);
            }

            DB::commit();
            return redirect()->route('kerusakanteknik.index')
                ->with('success', 'Data kerusakan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }




    // Hapus data
    public function destroy($id)
    {
        // Ambil data utama
        $data = KerusakanTeknik::findOrFail($id);

        // Hapus data sparepart berdasarkan sparepart_cod
        DB::table('sparepart')->where('sparepart_cod', $data->sparepart_cod)->delete();

        // Hapus data utama
        $data->delete();

        return redirect()->route('kerusakanteknik.index')
            ->with('success', 'Data dan sparepart terkait berhasil dihapus');
    }


    public function laporanperline(Request $request)
    {
        $periodes = $request->input('periode', date('Y-m'));
        [$tahun, $bulan] = explode('-', $periodes);

        // =======================
        //  DATA CAPPING PER LINE
        // =======================
        $data = DB::table('capping')
            ->select(
                'capping.LINE',
                DB::raw('MONTH(capping.CAPPINGTGL) AS bulan'),
                DB::raw('COUNT(DISTINCT CONCAT(capping.CAPPINGTGL, "-", capping.SHIFT)) AS jml_shift'),
                DB::raw('SUM(capping.PROD_SKRP) AS Prod_qty'),
                DB::raw('(SUM(capping.PROD_SKRP) / COUNT(DISTINCT CONCAT(capping.CAPPINGTGL, "-", capping.SHIFT))) AS Rata_shift')
            )
            ->whereYear('capping.CAPPINGTGL', $tahun)
            ->whereMonth('capping.CAPPINGTGL', $bulan)
            ->where('capping.isDeleted', 0)
            ->groupBy('capping.LINE', DB::raw('MONTH(capping.CAPPINGTGL)'))
            ->get()
            ->keyBy('LINE');   // ← supaya foreach per line


        // =======================
        //  DATA KERUSAKAN PER MESIN
        // =======================
        $laporan = KerusakanTeknik::select(
            'lokasi_line',
            'no_mesin as kode',
            'nama_mesin',
            DB::raw('SUM(durasi_jam) as stop_time'),
            DB::raw('COUNT(id) as kasus')
        )
            ->whereYear('tgl', $tahun)
            ->whereMonth('tgl', $bulan)
            ->groupBy('lokasi_line', 'no_mesin', 'nama_mesin')
            ->orderBy('lokasi_line')
            ->orderBy('no_mesin')
            ->get();

        // Kelompokkan per line
        $grouped = $laporan->groupBy('lokasi_line');


        // =======================
        //  GABUNGKAN & HITUNG PERSENTASE
        // =======================
        $hasil = [];

        /** @var \stdClass $cap */
        foreach ($data as $line => $cap) {

            // Data mesin pada line bersangkutan
            $items = $grouped[$line] ?? collect([]);

            // total kasus per line
            $total_kasus = $items->sum('kasus');

            // total stop time per line
            $total_stop_time = $items->sum('stop_time');

            // jumlah shift dari tabel capping
            $jml_shift = $cap->jml_shift;

            // Rumus: (total kasus / (jumlah shift × 8)) × 100
            $persentase_downtime = ($jml_shift > 0)
                ? (($total_stop_time / ($jml_shift * 8)) * 100)
                : 0;

            // dd($total_kasus, $jml_shift, $persentase_downtime);
            // Simpan hasil per line
            $hasil[$line] = [
                'line' => $line,
                'jml_shift' => $jml_shift,
                'prod_qty' => $cap->Prod_qty,
                'total_stop_time' => $total_stop_time,
                'total_kasus' => $total_kasus,
                'persentase_downtime' => $persentase_downtime,
                'detail_mesin' => $items,
            ];
        }

        // dd($hasil);

        return view('teknik.perbaikanteknik.laporanperline', [
            'hasil'    => $hasil,
            'grouped'  => $grouped,
            'periodes' => $periodes,
        ]);
    }



    public function exportLaporanPerLine(Request $request)
    {
        $periodes = $request->input('periode', date('Y-m'));
        [$tahun, $bulan] = explode('-', $periodes);

        // =======================
        //  DATA CAPPING PER LINE
        // =======================
        $data = DB::table('capping')
            ->select(
                'capping.LINE',
                DB::raw('MONTH(capping.CAPPINGTGL) AS bulan'),
                DB::raw('COUNT(DISTINCT CONCAT(capping.CAPPINGTGL, "-", capping.SHIFT)) AS jml_shift'),
                DB::raw('SUM(capping.PROD_SKRP) AS Prod_qty'),
                DB::raw('(SUM(capping.PROD_SKRP) / COUNT(DISTINCT CONCAT(capping.CAPPINGTGL, "-", capping.SHIFT))) AS Rata_shift')
            )
            ->whereYear('capping.CAPPINGTGL', $tahun)
            ->whereMonth('capping.CAPPINGTGL', $bulan)
            ->where('capping.isDeleted', 0)
            ->groupBy('capping.LINE', DB::raw('MONTH(capping.CAPPINGTGL)'))
            ->get()
            ->keyBy('LINE');


        // =======================
        //  DATA KERUSAKAN PER MESIN
        // =======================
        $laporan = KerusakanTeknik::select(
            'lokasi_line',
            'no_mesin as kode',
            'nama_mesin',
            DB::raw('SUM(durasi_jam) as stop_time'),
            DB::raw('COUNT(id) as kasus')
        )
            ->whereYear('tgl', $tahun)
            ->whereMonth('tgl', $bulan)
            ->groupBy('lokasi_line', 'no_mesin', 'nama_mesin')
            ->orderBy('lokasi_line')
            ->orderBy('no_mesin')
            ->get();

        $grouped = $laporan->groupBy('lokasi_line');


        // =======================
        //  GABUNGKAN & HITUNG
        // =======================
        $hasil = [];

        foreach ($data as $line => $cap) {

            $items = $grouped[$line] ?? collect([]);

            $total_kasus = $items->sum('kasus');
            $total_stop_time = $items->sum('stop_time');
            $jml_shift = $cap->jml_shift;

            // Rumus benar: STOP TIME / (Total Shift × 8 jam)
            $persentase_downtime = ($jml_shift > 0)
                ? (($total_stop_time / ($jml_shift * 8)) * 100)
                : 0;

            $hasil[$line] = [
                'line' => $line,
                'jml_shift' => $jml_shift,
                'prod_qty' => $cap->Prod_qty,
                'total_stop_time' => $total_stop_time,
                'total_kasus' => $total_kasus,
                'persentase_downtime' => $persentase_downtime,
                'detail_mesin' => $items,
            ];
        }

        // ==========================================
        // EXPORT EXCEL (menggunakan $hasil)
        // ==========================================

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Rekapitulasi");

        // STYLE
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F81BD']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $styleTable = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $styleTotal = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D9D9D9']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        // HEADER
        $sheet->setCellValue('A1', "Laporan Per Line - $periodes");
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 3;

        // ============================
        //   DETAIL PER LINE
        // ============================
        foreach ($hasil as $line => $val) {

            $sheet->setCellValue("A{$row}", "Line: {$line}");
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;

            // HEADER TABLE
            $sheet->fromArray(['KODE', 'NAMA MESIN', 'Stop Time', 'Case'], null, "A{$row}");
            $sheet->getStyle("A{$row}:D{$row}")->applyFromArray($styleHeader);
            $row++;

            $startTable = $row;

            // DETAIL MESIN
            foreach ($val['detail_mesin'] as $d) {
                $sheet->setCellValue("A{$row}", $d->kode);
                $sheet->setCellValue("B{$row}", $d->nama_mesin);
                $sheet->setCellValue("C{$row}", number_format($d->stop_time, 2));
                $sheet->setCellValue("D{$row}", $d->kasus);
                $row++;
            }

            // TOTAL
            $sheet->setCellValue("A{$row}", "TOTAL");
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->setCellValue("C{$row}", number_format($val['total_stop_time'], 2));
            $sheet->setCellValue("D{$row}", $val['total_kasus']);
            $sheet->getStyle("A{$row}:D{$row}")->applyFromArray($styleTotal);
            $row++;

            // PERSENTASE
            $sheet->setCellValue("A{$row}", "Persentase DT / 8jam");
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->setCellValue("C{$row}", number_format($val['persentase_downtime'], 2) . '%');
            $row += 2;

            $sheet->getStyle("A{$startTable}:D" . ($row - 3))->applyFromArray($styleTable);
        }

        // ============================
        //   REKAP PER LINE
        // ============================
        $sheet->setCellValue("A{$row}", "Rekapitulasi Per Line");
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);
        $row++;

        // HEADER REKAP
        $sheet->fromArray(['No.', 'Line', 'Shift', 'Stop Time', 'Case', '% DT'], null, "A{$row}");
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($styleHeader);
        $headerRow = $row;
        $row++;

        $no = 1;
        $startRekap = $row;

        $tStop = 0;
        $tCase = 0;

        foreach ($hasil as $line => $v) {

            $sheet->fromArray([
                $no++,
                $line,
                $v['jml_shift'],
                number_format($v['total_stop_time'], 2),
                $v['total_kasus'],
                number_format($v['persentase_downtime'], 2) . '%'
            ], null, "A{$row}");

            $tStop += $v['total_stop_time'];
            $tCase += $v['total_kasus'];

            $row++;
        }

        // TOTAL ROW
        $sheet->fromArray([
            'TOTAL',
            '',
            '',
            number_format($tStop, 2),
            $tCase,
            '-'
        ], null, "A{$row}");

        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($styleTotal);
        $row++;

        $endRekap = $row;

        $sheet->getStyle("A{$startRekap}:F{$endRekap}")->applyFromArray($styleTable);


        // ============================
        //   CHART
        // ============================
        $categories = new DataSeriesValues(
            'String',
            "Rekapitulasi!\$B\${$startRekap}:\$B" . ($endRekap - 1)
        );
        $values1 = new DataSeriesValues(
            'Number',
            "Rekapitulasi!\$D\${$startRekap}:\$D" . ($endRekap - 1)
        );
        $values2 = new DataSeriesValues(
            'Number',
            "Rekapitulasi!\$E\${$startRekap}:\$E" . ($endRekap - 1)
        );

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            [0, 1],
            [
                new DataSeriesValues('String', "Rekapitulasi!\$D\${$headerRow}"),
                new DataSeriesValues('String', "Rekapitulasi!\$E\${$headerRow}")
            ],
            [$categories],
            [$values1, $values2]
        );

        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
        $title = new Title("Grafik Rekapitulasi Per Line");

        $chart = new Chart('chart1', $title, $legend, $plotArea);
        $chart->setTopLeftPosition("H3");
        $chart->setBottomRightPosition("O20");

        $sheet->addChart($chart);

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Laporan_Per_Line_{$periodes}.xlsx";
        $filePath = storage_path("app/public/{$filename}");

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(false);
    }


    public function laporanpertahun(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data per bulan, per line
        $laporan = KerusakanTeknik::select(
            'lokasi_line',
            DB::raw('MONTH(tgl) as bulan'),
            DB::raw('SUM(durasi_jam) as total_stop_time'),
            DB::raw('COUNT(id) as total_kasus')
        )
            ->whereYear('tgl', $tahun)
            ->groupBy('lokasi_line', DB::raw('MONTH(tgl)'))
            ->orderBy('lokasi_line')
            ->orderBy(DB::raw('MONTH(tgl)'))
            ->get();

        // Kelompokkan berdasarkan line → bulan
        $grouped = $laporan->groupBy('lokasi_line');

        // Supaya tiap bulan Jan–Des selalu ada (meski nol)
        $data = [];
        foreach ($grouped as $line => $rows) {
            $data[$line] = [];
            for ($i = 1; $i <= 12; $i++) {
                $row = $rows->firstWhere('bulan', $i);
                $data[$line][$i] = [
                    'stop_time' => $row->total_stop_time ?? 0,
                    'kasus'     => $row->total_kasus ?? 0,
                ];
            }
        }

        return view('admin/kerusakanteknik/laporanpertahun', [
            'data' => $data,
            'tahun' => $tahun,
        ]);
    }

    public function exportPertahunExcel(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $laporan = KerusakanTeknik::select(
            'lokasi_line',
            DB::raw('MONTH(tgl) as bulan'),
            DB::raw('SUM(durasi_jam) as total_stop_time'),
            DB::raw('COUNT(id) as total_kasus')
        )
            ->whereYear('tgl', $tahun)
            ->groupBy('lokasi_line', DB::raw('MONTH(tgl)'))
            ->orderBy('lokasi_line')
            ->orderBy(DB::raw('MONTH(tgl)'))
            ->get();

        $grouped = $laporan->groupBy('lokasi_line');

        $data = [];
        foreach ($grouped as $line => $rows) {
            $data[$line] = [];
            for ($i = 1; $i <= 12; $i++) {
                $row = $rows->firstWhere('bulan', $i);
                $data[$line][$i] = [
                    'stop_time' => $row->total_stop_time ?? 0,
                    'kasus'     => $row->total_kasus ?? 0,
                ];
            }
        }
        // dd($data);
        // ========== PHPOffice ==========
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Laporan $tahun");

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $rowStart = 1;
        foreach ($data as $line => $bulanan) {
            // Judul line
            $sheet->setCellValue("A{$rowStart}", "Line $line - Tahun $tahun");
            $sheet->getStyle("A{$rowStart}")->getFont()->setBold(true)->setSize(12);
            $rowStart++;

            // Header
            $sheet->setCellValue("A{$rowStart}", "Bulan");
            $sheet->setCellValue("B{$rowStart}", "Stop Time (Jam)");
            $sheet->getStyle("A{$rowStart}:B{$rowStart}")->getFont()->setBold(true);
            $sheet->getStyle("A{$rowStart}:B{$rowStart}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowStart++;

            $rowDataStart = $rowStart;
            foreach ($bulanLabels as $idx => $bulan) {
                $sheet->setCellValue("A{$rowStart}", $bulan);
                $sheet->setCellValue("B{$rowStart}", $bulanan[$idx + 1]['stop_time']);
                $rowStart++;
            }
            $rowDataEnd = $rowStart - 1;

            // Format angka 2 desimal
            $sheet->getStyle("B{$rowDataStart}:B{$rowDataEnd}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

            // Total per line
            $sheet->setCellValue("A{$rowStart}", "Total");
            $sheet->setCellValue("B{$rowStart}", "=SUM(B{$rowDataStart}:B{$rowDataEnd})");
            $sheet->getStyle("A{$rowStart}:B{$rowStart}")->getFont()->setBold(true);
            $sheet->getStyle("B{$rowStart}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

            // Border tabel + total
            $sheet->getStyle("A" . ($rowDataStart - 1) . ":B{$rowStart}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);

            $rowStart++;

            // ===== Chart =====
            $categories = [new DataSeriesValues('String', "'Laporan $tahun'!\$A\${$rowDataStart}:\$A\${$rowDataEnd}", null, 12)];
            $values     = [new DataSeriesValues('Number', "'Laporan $tahun'!\$B\${$rowDataStart}:\$B\${$rowDataEnd}", null, 12)];

            $series = new DataSeries(
                DataSeries::TYPE_BARCHART,
                DataSeries::GROUPING_CLUSTERED,
                range(0, count($values) - 1),
                [],
                $categories,
                $values
            );
            $series->setPlotDirection(DataSeries::DIRECTION_COL);

            $plotArea = new PlotArea(null, [$series]);
            $chart = new Chart(
                "chart_line_$line",
                new Title("Stop Time Line $line"),
                new Legend(Legend::POSITION_RIGHT, null, false),
                $plotArea
            );

            // Posisi chart
            $chart->setTopLeftPosition("D" . ($rowDataStart - 1));
            $chart->setBottomRightPosition("K" . ($rowDataStart + 15));
            $sheet->addChart($chart);

            $rowStart += 3; // spasi antar line
        }

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);

        $fileName = "Laporan_Downtime_$tahun.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}
