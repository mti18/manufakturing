<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Account;
use App\Models\Jurnal;
use App\Models\MasterJurnal;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class JurnalController extends Controller
{
    public function jurnal($bulan, $tahun, $type)
    {
        if ($type == 0) {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'umum')->orderBy('tanggal')->get();
        } else if ($type == 1) {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'penyesuaian')->orderBy('tanggal')->get();
        } else {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'penutup')->orderBy('tanggal')->get();
        }
        return response()->json($data);
    }
    public function ReportJurnal($bulan, $tahun, $type)
    {
        if ($type == 0) {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'umum')->orderBy('tanggal')->get();
            $tp = "umum";
        } else if ($type == 1) {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'penyesuaian')->orderBy('tanggal')->get();
            $tp = "penyesuaian";
        } else {
            $data = MasterJurnal::with(['jurnal_item', 'jurnal_item.account'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('type', 'penutup')->orderBy('tanggal')->get();
            $tp = "penutup";
        }

        $spreadsheet = new Spreadsheet();

        $debit = 0;
        $kredit = 0;

        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Buku Besar');
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
        $spreadsheet->getActiveSheet()->mergeCells('A1:E1');
        $sheet->setCellValue('A1', strtoupper("LAPORAN JURNAL " . strtoupper($tp) . " " . AppHelper::get_bulan($bulan) . " Tahun " . $tahun));
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
       



        $a = 3;
        $spreadsheet->getActiveSheet()->getStyle('A' . $a . ':E' . $a)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');

        $sheet->setCellValue('A' . $a, "Tanggal");
        $sheet->setCellValue('B' . $a, "Nama Akun");
        $sheet->setCellValue('C' . $a, "Keterangan");
        $sheet->setCellValue('D' . $a, "Debit");
        $sheet->setCellValue('E' . $a, "Kredit");

        foreach ($data as $item) {
            $b = $a + 1;
            $merge = $b + count($item->jurnal_item) - 1;
            $spreadsheet->getActiveSheet()->mergeCells('A' . $b . ':A' . $merge);
            $sheet->setCellValue('A' . $b, AppHelper::tanggal_indo($item->tanggal));

            foreach ($item->jurnal_item as $citem) {
                $debit = $debit + $citem->debit;
                $kredit = $kredit + $citem->kredit;

                $sheet->setCellValue('B' . $b, $citem->account->nm_account);
                $sheet->setCellValue('C' . $b, $citem->keterangan);
                $sheet->setCellValue('D' . $b, AppHelper::rupiah($citem->debit));
                $sheet->setCellValue('E' . $b, AppHelper::rupiah($citem->kredit));
                $b++;
            }


            $a = $merge;
        }
        $merge = $merge + 1;
        $spreadsheet->getActiveSheet()->mergeCells('A' . $merge . ':C' . $merge);

        $sheet->setCellValue('A' . $merge, "TOTAL");
        $sheet->setCellValue('D' . $merge, AppHelper::rupiah($debit));
        $sheet->setCellValue('E' . $merge, AppHelper::rupiah($kredit));

        $spreadsheet->getActiveSheet()->getStyle('A1:E' . $b)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A' . 3 . ':E' . $merge)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));

        $spreadsheet->getActiveSheet()->getStyle('A' . $merge . ':E' . $merge)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');





        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="LAPORAN EXCEL JURNAL PERIODE ' . AppHelper::get_bulan($bulan) . ' Tahun ' . $tahun . '.xlsx"');
        $writer->save('php://output');
    }
    public function ReportWorksheet(Request $request, $tahun)
    {

        $data = Account::with(['umum' => function ($q) use ($tahun) {
            $q->whereHas("masterjurnal", function ($q) use ($tahun) {
                $q->whereYear("tanggal", $tahun);
            });
        }, 'penyesuaian' => function ($q) use ($tahun) {
            $q->whereHas("masterjurnal", function ($q) use ($tahun) {
                $q->whereYear("tanggal", $tahun);
            });
        }, 'parent.nodes'])->doesntHave('nodes')->get();

        $data = Account::doesntHave('nodes')->orderBy('kode_account')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Worksheet');
        $sheet->getTabColor()->setRGB('ff6666');
        // Default Font Style
        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Palatino Linotype'
            ]
        ]);
        //Header
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('A1:L1');
        $sheet->setCellValue('A1', strtoupper("LAPORAN Worksheet" . " Tahun " . $tahun));
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);


        $spreadsheet->getActiveSheet()->mergeCells('A3:A4');
        $spreadsheet->getActiveSheet()->mergeCells('B3:B4');
        $spreadsheet->getActiveSheet()->mergeCells('C3:D3');
        $spreadsheet->getActiveSheet()->mergeCells('E3:F3');
        $spreadsheet->getActiveSheet()->mergeCells('G3:H3');
        $spreadsheet->getActiveSheet()->mergeCells('I3:J3');


        $sheet->setCellValue('A3', "No Akun");
        $sheet->setCellValue('B3', "Nama Akun");
        $sheet->setCellValue('C3', "Neraca Saldo");
        $sheet->setCellValue('C4', "Debit");
        $sheet->setCellValue('D4', "Kredit");
        $sheet->setCellValue('E3', "Jurnal Penyesuaian");
        $sheet->setCellValue('E4', "Debit");
        $sheet->setCellValue('F4', "Kredit");
        $sheet->setCellValue('G3', "Neraca Saldo Disesuaikan");
        $sheet->setCellValue('G4', "Debit");
        $sheet->setCellValue('H4', "Kredit");
        $sheet->setCellValue('I3', "Laba Rugi");
        $sheet->setCellValue('I4', "Debit");
        $sheet->setCellValue('J4', "Kredit");
        $sheet->setCellValue('K3', "Neraca");
        $sheet->setCellValue('K4', "Debit");
        $sheet->setCellValue('L4', "Kredit");

        $spreadsheet->getActiveSheet()->getStyle('A3:L4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');
        $spreadsheet->getActiveSheet()->getStyle('A3:L4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A3:L4')->getFont()->setBold(true);



        $a = 5;

        $nom_kredit = [];
        $nom_debit = [];


        foreach ($data as $item) {
            $sheet->setCellValue('A' . $a, $item->kode_account);
            $sheet->setCellValue('B' . $a, $item->nm_account);

            //neraca saldo            
            $total = 0;
            foreach ($item->umum as $umum) {
                $total += $umum->debit - $umum->kredit;
            }

            if ($total > 0) {
                $sheet->setCellValue('C' . $a, $total);
                $sheet->setCellValue('D' . $a, 0);
            } else {
                $sheet->setCellValue('C' . $a, 0);
                $sheet->setCellValue('D' . $a, abs($total));
            }

            //jurnal penyesuaian

            $total_jps = 0;
            foreach ($item->penyesuaian as $penyesuaian) {
                $total_jps += $penyesuaian->debit - $penyesuaian->kredit;
            }

            if ($total_jps > 0) {
                $sheet->setCellValue('E' . $a, $total_jps);
                $sheet->setCellValue('F' . $a, 0);
            } else {
                $sheet->setCellValue('E' . $a, 0);
                $sheet->setCellValue('F' . $a, abs($total_jps));
            }

            //NSD

            $total_nsd = $total + $total_jps;

            if ($total_nsd > 0) {
                $sheet->setCellValue('G' . $a, $total_nsd);
                $sheet->setCellValue('H' . $a, 0);
            } else {
                $sheet->setCellValue('G' . $a, 0);
                $sheet->setCellValue('H' . $a, abs($total_nsd));
            }

            if (@$item->parent->type == "rill") {
                if ($total_nsd > 0) {
                    $sheet->setCellValue('J' . $a, "-");
                    $sheet->setCellValue('I' . $a, "-");
                    $sheet->setCellValue('K' . $a, $total_nsd);
                    $sheet->setCellValue('L' . $a, 0);
                } else {
                    $sheet->setCellValue('K' . $a, 0);
                    $sheet->setCellValue('L' . $a, abs($total_nsd));
                    $sheet->setCellValue('J' . $a, "-");
                    $sheet->setCellValue('I' . $a, "-");
                }
            } else {
                if ($total_nsd > 0) {
                    $sheet->setCellValue('K' . $a, "-");
                    $sheet->setCellValue('L' . $a, "-");
                    $sheet->setCellValue('I' . $a, $total_nsd);
                    $sheet->setCellValue('J' . $a, 0);
                } else {
                    $sheet->setCellValue('K' . $a, "-");
                    $sheet->setCellValue('L' . $a, "-");
                    $sheet->setCellValue('I' . $a, 0);
                    $sheet->setCellValue('J' . $a, abs($total_nsd));
                }

                if (@$item->parent->account_type == "debit") {
                    $item->total = abs($total_nsd);
                    array_push($nom_debit, $item);
                } else {
                    $item->total = abs($total_nsd);
                    array_push($nom_kredit, $item);
                }
            }


            $a += 1;
        }

        $b = $a + 2;
        $spreadsheet->getActiveSheet()->mergeCells('A' . $a . ':B' . $b);
        $sheet->setCellValue('a' . $a, "Total");
        $sheet->setCellValue('C' . $a, "=SUM(C5:C" . ($a - 1) . ")");
        $sheet->setCellValue('D' . $a, "=SUM(D5:D" . ($a - 1) . ")");

        $sheet->setCellValue('E' . $a, "=SUM(E5:E" . ($a - 1) . ")");
        $sheet->setCellValue('F' . $a, "=SUM(F5:F" . ($a - 1) . ")");

        $sheet->setCellValue('G' . $a, "=SUM(G5:G" . ($a - 1) . ")");
        $sheet->setCellValue('H' . $a, "=SUM(H5:H" . ($a - 1) . ")");

        $sheet->setCellValue('I' . $a, "=SUM(I5:I" . ($a - 1) . ")");
        $sheet->setCellValue('J' . $a, "=SUM(J5:J" . ($a - 1) . ")");

        $sheet->setCellValue('K' . $a, "=SUM(K5:K" . ($a - 1) . ")");
        $sheet->setCellValue('L' . $a, "=SUM(L5:L" . ($a - 1) . ")");



        $sheet->setCellValue('L' . ($a + 1), "=(K" . $a . "-L" . $a . ")");
        $sheet->setCellValue('I' . ($a + 1), "=(J" . $a . "-I" . $a . ")");

        $lr = $spreadsheet->getActiveSheet()->getCell('L' . ($a + 1))->getCalculatedValue();
        $ns = $spreadsheet->getActiveSheet()->getCell('I' . ($a + 1))->getCalculatedValue();

        if ($lr < 0) {
            $sheet->setCellValue('I' . ($a + 1), "");
            $sheet->setCellValue('J' . ($a + 1), abs($lr));
        } else {
            $sheet->setCellValue('I' . ($a + 1), abs($lr));
            $sheet->setCellValue('J' . ($a + 1), "");
        }
        if ($ns < 0) {
            $sheet->setCellValue('K' . ($a + 1), abs($ns));
            $sheet->setCellValue('L' . ($a + 1), "");
        } else {
            $sheet->setCellValue('K' . ($a + 1), "");
            $sheet->setCellValue('L' . ($a + 1), abs($ns));
        }

        $sheet->setCellValue('K' . ($a + 2), "=SUM(K" . $a . ":K" . ($a + 1) . ")");
        $sheet->setCellValue('L' . ($a + 2), "=SUM(L" . $a . ":L" . ($a + 1) . ")");
        $sheet->setCellValue('I' . ($a + 2), "=SUM(I" . $a . ":I" . ($a + 1) . ")");
        $sheet->setCellValue('J' . ($a + 2), "=SUM(J" . $a . ":J" . ($a + 1) . ")");


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('C5:L' . $b)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // ->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_RP);
           

        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A3:L' . $b)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));

        $spreadsheet->getActiveSheet()->getStyle('A1:L' . $b)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A' . $a . ':L' . $b)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $a . ':L' . $b)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="LAPORAN EXCEL WORKSHEET PERIODE Tahun ' . ($tahun) . '.xlsx"');
        $writer->save('php://output');
    }

}
