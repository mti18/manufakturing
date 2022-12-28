<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Account;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;


class NeracaSaldoController extends Controller
{
    public function neraca($bulan, $tahun, $type)
    {

        $data = Account::with(['jurnal_item'  => function ($q) use ($bulan, $tahun, $type) {
            $q->whereHas('masterjurnal', function ($q) use ($bulan, $tahun, $type) {
                if ($type == 0) {
                    $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                } else {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
                }
            });
        }, 'jurnal_item.masterjurnal'])->whereHas('jurnal_item.masterjurnal', function ($q) use ($bulan, $tahun, $type) {
            if ($type == 0) {
                $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            } else {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
            }
        })->get();

        $data = $data->sortBy('kode_account');

        $data->map(function ($a) use ($type) {
            $a->sum = $a->jurnal_item->sum('debit') - $a->jurnal_item->sum('kredit');

            return $a;
        });

        return response()->json($data);
    }
    public function ReportNeracaSaldo($bulan, $tahun, $type)
    {
        $data = Account::with(['jurnal_item'  => function ($q) use ($bulan, $tahun, $type) {
            $q->whereHas('masterjurnal', function ($q) use ($bulan, $tahun, $type) {
                if ($type == 0) {
                    $q->where('type', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                    $tp = "belum disesuaikan";
                } else {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
                    $tp = "penyesuaian";
                }
            });
        }, 'jurnal_item.masterjurnal'])->whereHas('jurnal_item.masterjurnal', function ($q) use ($bulan, $tahun, $type) {
            if ($type == 0) {
                $q->where('type', '=', 'umum')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            } else {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('type', ['penyesuaian', 'umum']);
            }
        })->get();

        $data = $data->sortBy('kode_account');

        $data->map(function ($a) use ($type) {
            $a->sum = $a->jurnal_item->sum('debit') - $a->jurnal_item->sum('kredit');

            return $a;
        });

        if ($type == 0) {
            $tp = "belum disesuaikan";
        } else {
            $tp = "penyesuaian";
        }
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Neraca saldo ' . $tp);
        $sheet->getTabColor()->setRGB('ff6666');
        // Default Font Style
        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Arial Narrow'
            ]
        ]);

        //Header
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('A1:G1');
        $sheet->setCellValue('A1', strtoupper("LAPORAN Neraca Saldo " . $tp . " Bulan " . AppHelper::get_bulan($bulan) . " Tahun " . $tahun));
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $a = 3;

        $sheet->setCellValue('A' . $a, "No Akun");
        $sheet->setCellValue('B' . $a, "Nama Akun");
        $sheet->setCellValue('C' . $a, "Debit");
        $sheet->setCellValue('D' . $a, "Kredit");

        foreach ($data as $item) {
            $a = $a + 1;

            $sheet->setCellValue('A' . $a, $item->kode_account);
            $sheet->setCellValue('B' . $a, $item->nm_account);


            if ($item->sum > 0) {
                $sheet->setCellValue('C' . $a, AppHelper::rupiah($item->sum));
                $sheet->setCellValue('D' . $a, "-");
            } else {
                $sheet->setCellValue('C' . $a, "-");
                $sheet->setCellValue('D' . $a, AppHelper::rupiah(abs($item->sum)));
            }
        }

        $b = $a + 1;

        $spreadsheet->getActiveSheet()->mergeCells('A' . $b . ':B' . $b);
        $sheet->setCellValue('A' . $b, "Total");
        $debit = 0;
        $kredit = 0;

        foreach ($data as $item) {
            if ($item->sum > 0) {
                $debit += $item->sum;
            } else {
                $kredit += abs($item->sum);
            }
        }
        $sheet->setCellValue('C' . $b, AppHelper::rupiah($debit));
        $sheet->setCellValue('D' . $b, AppHelper::rupiah($kredit));

        $spreadsheet->getActiveSheet()->getStyle('A1:E' . $b)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A3:D' . $b)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));

        $spreadsheet->getActiveSheet()->getStyle('A' . $b . ':D' . $b)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="LAPORAN EXCEL NERACA SALDO PERIODE Tahun ' . $tahun . '.xlsx"');
        $writer->save('php://output');
    }
    
}
