<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Account;
use App\Models\AssetJurnal;
use App\Models\Jurnal;
use App\Models\MasterJurnal;
use App\Models\ReportJurnal;
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

            if (@$item->parent->account_type == "rill") {
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

                if (@$item->parent->type == "debit") {
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
            // ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
           

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
    public function ReportLaporan(Request $request, $tahun)
    {

        $data = Account::with(['umum' => function ($q) use ($tahun) {
            $q->whereHas("MasterJurnal", function ($q) use ($tahun) {
                $q->whereYear("tanggal", $tahun);
            });
        }, 'penyesuaian' => function ($q) use ($tahun) {
            $q->whereHas("MasterJurnal", function ($q) use ($tahun) {
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
                'name'  => 'Arial Narrow'
            ]
        ]);
        


        $nom_kredit = [];
        $nom_debit = [];


        foreach ($data as $item) {
                    $total = 0;
                    foreach ($item->umum as $umum) {
                        $total += $umum->debit - $umum->kredit;
                    }
        
                    $total_jps = 0;
                    foreach ($item->penyesuaian as $penyesuaian) {
                        $total_jps += $penyesuaian->debit - $penyesuaian->kredit;
                    }
                    $total_nsd = $total + $total_jps;
                    if ($item->account_type != "rill") {
        
                        if ($item->type == "debit") {
                            $item->total = abs($total_nsd);
                            array_push($nom_debit, $item);
                        } else {
                            $item->total = abs($total_nsd);
                            array_push($nom_kredit, $item);
                        }
                    }
                }
        
                $debit = 0;
                $kredit = 0;
                foreach ($nom_debit as $item) {
                    $debit += $item->total;
                }
        
                foreach ($nom_kredit as $item) {
                    $kredit += $item->total;
                }
        


        $sheet->getTabColor()->setRGB('ffc966');

        $spreadsheet->getActiveSheet()->setTitle('LR');

        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Arial Narrow'
            ]
        ]);
        //Header
        $sheet->setCellValue('A1', strtoupper("LAPORAN LABA - RUGI"));
        $sheet->setCellValue('A2', strtoupper("Periode: Tahun " . $tahun));

        // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);





        $a = 8;
        $b = 8;

        $total_pendapatan = 0;
        $total_pengeluaran = 0;

        $sheet->setCellValue('A7', "PENDAPATAN :");
        $spreadsheet->getActiveSheet()->mergeCells('A7:B7');
        $sheet->getStyle('A7')->getFont()->setBold(true);
        $sheet->getStyle('A7')->getFont()->setUnderline(true);
        
        
        
        // return $nom_debit;
        
        
        foreach ($nom_debit as $item) {
            if ($item->total == 0) {
                continue;
            }
            $sheet->setCellValue('B' . $a, "(" . $item->kode_account . ") - " . $item->nm_account);
            $sheet->setCellValue('C' . $a, $item->total);
            $total_pendapatan += $item->total;
            $a++;
        }
        
        $sheet->getStyle("B$a:C$a")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B' . $a)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("B$a:C$a")->getFont()->setUnderline(true);
        
        $sheet->setCellValue('C' . $a, "=sum(C" . $b . ":C" . $a . ")");
        $spreadsheet->getActiveSheet()->getStyle("B$a:C$a")->getBorders()
            ->getTop()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));
            
            
            $a += 4;
            $b = $a;
            
            $sheet->setCellValue('A' . ($a - 1), "PENGELUARAN / BIAYA USAHA");
            $spreadsheet->getActiveSheet()->mergeCells('A' . ($a - 1).":B".($a - 1));
            $sheet->getStyle('A' . ($a - 1))->getFont()->setBold(true);
            $sheet->getStyle('A' . ($a - 1))->getFont()->setUnderline(true);
            foreach ($nom_kredit as $item) {
                if ($item->total == 0) {
                    continue;
                }
                $sheet->setCellValue('B' . $a, strtoupper("(" . $item->kode_account . ") - " . $item->nm_account));
                $sheet->setCellValue('C' . $a, $item->total);
                $total_pengeluaran += $item->total;
                $a++;
            }
            
        $sheet->getStyle("B$a:C$a")->getFont()->setBold(true);
        $sheet->getStyle("B$a:C$a")->getFont()->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle('B' . $a)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('C' . $a, "=sum(C" . $b . ":C" . $a . ")");
        $spreadsheet->getActiveSheet()->getStyle("B$a:C$a")->getBorders()
            ->getTop()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));;
            
            if ($total_pendapatan - $total_pengeluaran > 0) {
                $oi = "laba bersih";
            } else if ($total_pendapatan - $total_pengeluaran == 0) {
            $oi = "laba rugi";
        } else {
            $oi = "rugi bersih";
        }
        
        $laba = $total_pendapatan - $total_pengeluaran;
        $a += 3;
        
        $sheet->setCellValue('B' . $a, strtoupper($oi));
        $sheet->setCellValue('C' . $a, abs($total_pendapatan - $total_pengeluaran));
        
        
        $sheet->getStyle("B$a:C$a")->getFont()->setBold(true);
        $sheet->getStyle("B$a:C$a")->getFont()->setUnderline(true);
        
        
        
        $spreadsheet->getActiveSheet()->getStyle('A5:D' . ($a + 5))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('ffffff');
            $spreadsheet->getActiveSheet()->getStyle('A5:D' . ($a + 5))->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));
            $sheet->getStyle('A3:C' . ($a + 5))
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
            $jurnalPenutup = ReportJurnal::where('tahun', $tahun)->first();
            $bulan = 12;
            
            
            $neraca = Account::with(['umum' => function ($q) use ($tahun, $bulan) {
                $q->whereHas('masterjurnal', function ($r) use ($bulan, $tahun) {
                    $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
                });
        },  'jurnal_item' => function ($q) use ($bulan, $tahun) {
            $q->whereHas('masterjurnal', function ($r) use ($bulan, $tahun) {
                $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        }, 'jurnal_item.masterjurnal' => function ($q) use ($tahun, $bulan) {
            $q->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
        }])->where("type", 'rill')
            ->get();


        $neraca->map(function ($a) {
            // $r->AcountGroup->map(function ($a) {
                $a->Account->map(function ($b) {
                    $totalk = $b->jurnal_item->sum('kredit');
                    $totald = $b->jurnal_item->sum('debit');

                    $b->total = $totald - $totalk;
                    return $b;
                });
                return $a;
            });
            // return $r;

        $spreadsheet->createSheet();
        $Nsheet = $spreadsheet->setActiveSheetIndex(1);
        $Nsheet->getTabColor()->setRGB('ffc966');

        $spreadsheet->getActiveSheet()->setTitle('N');

        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Arial Narrow'
            ]
        ]);
        //Header
        $Nsheet->setCellValue('A1', strtoupper("Neraca PER " . AppHelper::get_bulan($bulan) . " Tahun " . $tahun));

        // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $a = 4;

        $data = Account::with(['umum' => function ($q) use ($bulan, $tahun) {
            $q->whereHas("MasterJurnal", function ($q) use ($bulan, $tahun) {
                $q->whereYear('tanggal', $tahun);
            });
        }, 'penyesuaian' => function ($q) use ($bulan, $tahun) {
            $q->whereHas("MasterJurnal", function ($q) use ($bulan, $tahun) {
                $q->whereYear('tanggal', $tahun);
            });
        },])->get();

        $nom_debit = [];
        $nom_kredit = [];


        $aktif = [];
        $pasif = [];
        


        foreach ($data as $item) {
            $total = 0;
            foreach ($item->umum as $umum) {
                $total += $umum->debit - $umum->kredit;
            }

            $total_jps = 0;
            foreach ($item->penyesuaian as $penyesuaian) {
                $total_jps += $penyesuaian->debit - $penyesuaian->kredit;
            }
            $total_nsd = $total + $total_jps;
            if ($item->account_type != "rill") {

                if ($item->type == "debit") {
                    $item->total = abs($total_nsd);
                    array_push($nom_debit, $item);
                } else {
                    array_push($nom_kredit, $item);
                    $item->total = abs($total_nsd);
                }
            } else {
                if ($item->type == "debit") {
                    $item->total = $total_nsd;
                    array_push($aktif, $item);
                } else {
                    $item->total = -$total_nsd;
                    array_push($pasif, $item);
                }
                
            }
        }
        // return  $pasif ;
        
        $debit = 0;
        $kredit = 0;
        foreach ($nom_debit as $item) {
            $debit += $item->total;
        }

        foreach ($nom_kredit as $item) {
            $kredit += $item->total;
        }

        $modal = Account::with(['jurnal_item.masterjurnal' => function ($q) use ($tahun, $bulan) {
            $q->whereHas('jurnal_item.masterjurnal', function ($r) use ($bulan, $tahun) {
                $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        // }, 'AcountGroup.Acount' => function ($q) use ($bulan, $tahun) {
        //     $q->whereHas('jurnal_item.jurnal', function ($r) use ($bulan, $tahun) {
        //         $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
        //     });
        },  'jurnal_item' => function ($q) use ($bulan, $tahun) {
            $q->whereHas('masterjurnal', function ($r) use ($bulan, $tahun) {
                $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        }, 'jurnal_item.masterjurnal' => function ($q) use ($tahun, $bulan) {
            $q->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
        }])->where('id', $jurnalPenutup->account_id)->first();

        
        $total_modal = 0;
        
        // foreach ($modal->AcountGroup as $item) {
            foreach ($modal->jurnal_item as $citem) {
                $total_modal += $citem->debit - $citem->kredit;
            }
        // }

        if ($total_modal > 0) {
            $total_modal = -$total_modal;
        } else {
            $total_modal = abs($total_modal);
        }

        $a = 5;
        
        $Nsheet->setCellValue('A' . $a,   "AKTIVA");
        $Nsheet->setCellValue('E' . $a,   "PASIVA");
        $spreadsheet->getActiveSheet()->getStyle("A$a:H$a")->getFont()->setBold(true);
        
        $b = $a+3;
        $c = $a+3;

        foreach($aktif as $item){           
            $Nsheet->setCellValue('A' . $b,   $item->kode_account);
            $Nsheet->setCellValue('B' . $b,   strtoupper($item->nm_account));
            $Nsheet->setCellValue('C' . $b,   $item->total);
            $b++;
            
        }
        
        foreach($pasif as $item){     
            

            if($item->parent_id != $jurnalPenutup->account_id){
                $Nsheet->setCellValue('E' . $c,   $item->kode_account);
                $Nsheet->setCellValue('F' . $c,   strtoupper($item->nm_account));
                $Nsheet->setCellValue('G' . $c,   $item->total);
                $c++;
            }
        }
        
        $Nsheet->setCellValue('F' . $c,   "MODAL AKHIR ".($tahun));
        $Nsheet->setCellValue('G' . $c,   $total_modal + ($debit - $kredit));
        
        
        if($b > $c){
            $d = $b;
        } else {
            $d = $c;
        }
        $d += 2;

        $Nsheet->setCellValue('B' . $d, "TOTAL AKTIVA");
        $Nsheet->setCellValue('C' . $d, "=SUM(C8:C".($d-1).")");
        $Nsheet->setCellValue('F' . $d, "TOTAL PASIVA");
        $Nsheet->setCellValue('G' . $d, "=SUM(G8:G".($d-1).")");
        $spreadsheet->getActiveSheet()->getStyle("A$d:H$d")->getFont()->setBold(true);


                       $Nsheet->getStyle("C8:C$d")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                       $Nsheet->getStyle("G8:G$d")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


                            $spreadsheet->getActiveSheet()->getStyle("A4:D" . ($d + 2))->getBorders()

            ->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));
                            $spreadsheet->getActiveSheet()->getStyle("E4:H" . ($d + 2))->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));
                            $spreadsheet->getActiveSheet()->getStyle("A".($d-2).":H" . ($d + 2))->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));
                            $spreadsheet->getActiveSheet()->getStyle("A4:H6")->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));

                    

        // return $pasif;
        // foreach ($neraca as $pitem) {
        //     $Nsheet->setCellValue('A' . $a, $pitem->nm_acount_induk);
        //     $spreadsheet->getActiveSheet()->mergeCells("A$a:B$a");
        //     $jumlah = 0;
        //     ++$a;
        //     if ($pitem->id == $jurnalPenutup->acc_induk_id) {


        //         $Nsheet->setCellValue('B' . $a, "Modal");
        //         $spreadsheet->getActiveSheet()->mergeCells("B$a:C$a");
        //         ++$a;
        //         $b = $a;


        //         $Nsheet->setCellValue('C' . $a,   "Modal akhir ");
        //         $Nsheet->setCellValue('D' . $a,  $total_modal + ($debit - $kredit));
        //         $Nsheet->getStyle("D$a")
        //             ->getNumberFormat()
        //             ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        //         $a++;

        //         $Nsheet->setCellValue('D' . $a, "=Sum(D$b:D$a)");
        //         $spreadsheet->getActiveSheet()->getStyle("D$a")->getBorders()
        //             ->getTop()
        //             ->setBorderStyle(Border::BORDER_THIN)
        //             ->setColor(new Color('000000'));
        //         $Nsheet->getStyle("D$a")
        //             ->getNumberFormat()
        //             ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        //         $jumlah += $spreadsheet->getActiveSheet()->getCell("D$a")->getCalculatedValue();




        //         $a += 2;
        //     } else {
        //         foreach ($pitem->AcountGroup as $item) {
        //             $Nsheet->setCellValue('B' . $a, $item->nm_acount_group);
        //             $spreadsheet->getActiveSheet()->mergeCells("B$a:C$a");
        //             ++$a;
        //             $b = $a;
        //             foreach ($item->Acount as $citem) {

        //                 $Nsheet->setCellValue('C' . $a, $citem->kd_acount . " " . $citem->nm_acount);
        //                 $Nsheet->setCellValue('D' . $a, $citem->total);
        //                 $Nsheet->getStyle("D$a")
        //                     ->getNumberFormat()
        //                     ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        //                 $a++;
        //             }
        //             $Nsheet->setCellValue('D' . $a, "=Sum(D$b:D$a)");
        //             $spreadsheet->getActiveSheet()->getStyle("D$a")->getBorders()
        //                 ->getTop()
        //                 ->setBorderStyle(Border::BORDER_THIN)
        //                 ->setColor(new Color('000000'));
        //             $Nsheet->getStyle("D$a")
        //                 ->getNumberFormat()
        //                 ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        //             $jumlah += $spreadsheet->getActiveSheet()->getCell("D$a")->getCalculatedValue();




        //             $a += 2;
        //         }
        //     }

        //     $spreadsheet->getActiveSheet()->mergeCells("B$a:C$a");
        //     $Nsheet->setCellValue('B' . $a, "TOTAL " . $pitem->nm_acount_induk);
        //     $Nsheet->setCellValue('D' . $a, abs($jumlah));
        //     $Nsheet->getStyle("D$a")
        //         ->getNumberFormat()
        //         ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        //     $spreadsheet->getActiveSheet()->getStyle("B$a:D$a")->getBorders()
        //         ->getTop()
        //         ->setBorderStyle(Border::BORDER_THICK)
        //         ->setColor(new Color('000000'));

        //     $a += 3;
        // }

        // $spreadsheet->getActiveSheet()->getStyle("A3:D" . ($a + 2))->getBorders()
        //     ->getOutline()
        //     ->setBorderStyle(Border::BORDER_THICK)
        //     ->setColor(new Color('000000'));

            
        // $spreadsheet->getActiveSheet()->getStyle("A3:D" . ($a + 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        // ->getStartColor()->setARGB('ffffff');



        $tahun = $tahun - 1;

        $data = Account::with(['umum' => function ($q) use ($bulan, $tahun) {
            $q->whereHas("MasterJurnal", function ($q) use ($bulan, $tahun) {
                $q->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        }, 'penyesuaian' => function ($q) use ($bulan, $tahun) {
            $q->whereHas("MasterJurnal", function ($q) use ($bulan, $tahun) {
                $q->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        },])->get();

        $nom_debit = [];
        $nom_kredit = [];

        foreach ($data as $item) {
            $total = 0;
            foreach ($item->umum as $umum) {
                $total += $umum->debit - $umum->kredit;
            }

            $total_jps = 0;
            foreach ($item->penyesuaian as $penyesuaian) {
                $total_jps += $penyesuaian->debit - $penyesuaian->kredit;
            }
            $total_nsd = $total + $total_jps;
            if ($item->account_type != "rill") {

                if ($item->type == "debit") {
                    $item->total = abs($total_nsd);
                    array_push($nom_debit, $item);
                } else {
                    $item->total = abs($total_nsd);
                    array_push($nom_kredit, $item);
                }
            }
        }

        $debit = 0;
        $kredit = 0;
        foreach ($nom_debit as $item) {
            $debit += $item->total;
        }

        foreach ($nom_kredit as $item) {
            $kredit += $item->total;
        }

        $modal = Account::whereHas('jurnal_item.masterjurnal', function ($r) use ($bulan, $tahun) {
            $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
        })->where("account_type", 'rill')->get();


        $total_modal = 0;

        // foreach ($modal->AcountGroup as $item) {
            foreach ($modal as $citem) {
                foreach ($citem->jurnal_item as $ccitem) {
                    $total_modal += $ccitem->debit - $ccitem->kredit;
                }
            }
        // }

        if ($total_modal > 0) {
            $total_modal = -$total_modal;
        } else {
            $total_modal = abs($total_modal);
        }


        $spreadsheet->createSheet();
        $Msheet = $spreadsheet->setActiveSheetIndex(2);
        $Msheet->getTabColor()->setRGB('ffc966');

        $spreadsheet->getActiveSheet()->setTitle('PM');

        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Arial Narrow'
            ]
        ]);
        //Header
        $Msheet->setCellValue('A1', strtoupper("Perubahan Modal Tahun " . ($tahun + 1)));
        $Msheet->getStyle("D")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $Msheet->getStyle("E")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        // $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $a = 6;



        $tahun += 1;
        $modal = Account::with(['jurnal_item' => function ($q) use ($tahun, $bulan) {
            $q->whereHas('masterjurnal', function ($r) use ($bulan, $tahun) {
                $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        }, 'jurnal_item.masterjurnal'  => function ($q) use ($tahun, $bulan) {
            $q->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
        }, 'parent_id'])->where('parent_id', $jurnalPenutup->account_id)->whereHas('jurnal_item.masterjurnal', function ($q) use ($tahun) {
            $q->whereYear('tanggal', "<=", $tahun);
        })->get();

        $modal->map(function ($q) {
            $q->total = 0;

            foreach ($q->jurnal_item as $item) {
                $q->total  += $item->debit - $item->kredit;
            }
        });

        $Msheet->setCellValue("B$a", strtoupper("Modal Akhir Tahun " . ($tahun - 1)));
        $Msheet->setCellValue("D$a", ($total_modal + ($debit - $kredit)));
        $a += 1;

        foreach ($modal as $item) {
            $Msheet->setCellValue("A$a", strtoupper($item->kode_account));
            $Msheet->setCellValue("B$a", strtoupper($item->nm_account));
            $Msheet->setCellValue("D$a", abs($item->total));
            $a++;
        }

        $Msheet->setCellValue("E$a", "=SUM(D6:D$a)");
        $Msheet->getStyle("E$a")->getFont()->setUnderline(true);
        $Msheet->getStyle("E$a")->getFont()->setBold(true);
        $a += 2;
        $Msheet->setCellValue("B$a", strtoupper("$oi " . ($tahun)));
        $Msheet->setCellValue("E$a", $total_pendapatan - $total_pengeluaran);
        $Msheet->getStyle("E$a")->getFont()->setUnderline(true);
        $Msheet->getStyle("E$a")->getFont()->setBold(true);


        $Msheet->setCellValue("B" . ($a + 2), strtoupper("total perubahan modal"));
        $Msheet->setCellValue("E" . ($a + 2), "=SUM(E4:E$a)");


        $spreadsheet->getActiveSheet()->getStyle("B" . ($a + 2). ":E". ($a + 2))->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle("A4:F" . ($a+4))->getBorders()
        ->getOutline()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000000'));
        
        
        $spreadsheet->getActiveSheet()->getStyle("A4:F".($a+4))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('ffffff');



        
        $asset = AssetJurnal::with(['penyusutan' => function($q) use ($tahun, $bulan){
            $q->whereHas('masterjurnal', function ($r) use ($bulan, $tahun) {
                $r->whereYear('tanggal', $tahun);
            })->orderBy('tanggal', 'desc');
        }, 'penyusutan.masterjurnal' => function($q) use ($bulan, $tahun){
            $q->whereYear('tanggal', $tahun);
        }, 'penyusutan.masterjurnal.jurnal_item', 'PenyusutanAll' => function($q) use ($tahun, $bulan){
            $q->whereHas('masterjurnal', function ($r) use ($bulan, $tahun) {
                $r->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
            });
        },'PenyusutanAll.masterjurnal' => function($q) use ($bulan, $tahun){
            $q->where('tanggal', "<=", "$tahun-$bulan-" . date("t", strtotime($bulan)));
        }, 'PenyusutanAll.masterjurnal.jurnal_item'])->get();

        
        $asset->map(function($q) {

            $q->penyusutan->map(function($o){
                $o->jumlah_pen = $o->masterjurnal->jurnal_item->sum('debit');
                return $o;
            });
            
            $q->PenyusutanAll->map(function($p){
                $p->jumlah_penyusutan = $p->masterjurnal->jurnal_item->sum('debit');
                return $p;
            });
            
            
            $q->jumlah_peny = $q->penyusutan->sum('jumlah_pen');
            $q->nilai_buku = $q->price - $q->PenyusutanAll->sum('jumlah_penyusutan');
            $q->jumlah_penyusutan_all =  $q->PenyusutanAll->sum('jumlah_penyusutan');
            $q->jumlah_penusutan_seb = $q->jumlah_peny - $q->PenyusutanAll->sum('jumlah_penyusutan');
            $q->nilai_buku_seb = $q->price - $q->jumlah_penusutan_seb;
            
            return $q;
        });
        

        // return $asset;


        $spreadsheet->createSheet();
        $AKsheet = $spreadsheet->setActiveSheetIndex(3);
        $AKsheet->getTabColor()->setRGB('ffc966');

        $spreadsheet->getActiveSheet()->setTitle('PA');

        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Arial Narrow'
            ]
        ]);
        //Header
        $AKsheet->setCellValue('A1', strtoupper("PENYUSUTAN ASSET " . ($tahun)));
        // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
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
        
        
        $a = 4;
        
        $AKsheet->setCellValue("A$a", 'NO');
        $AKsheet->setCellValue("B$a", 'NAMA ASSET');
        $AKsheet->setCellValue("C$a", 'TAHUN PEROLEHAN');
        $AKsheet->setCellValue("D$a", 'HARGA PEROLEHAN');
        $AKsheet->setCellValue("E$a", 'AKM. PENYT. sd/'.($tahun-1));
        $AKsheet->setCellValue("F$a", 'NILAI BUKU');
        $AKsheet->setCellValue("G$a", 'METODE PENYUSUTAN');
        $AKsheet->setCellValue("H$a", 'GOL');
        $AKsheet->setCellValue("I$a", 'TARIF PENYUSUTAN');
        $AKsheet->setCellValue("J$a", 'PENYUSUTAN TERAKHIR');
        $AKsheet->setCellValue("K$a", 'PENYUSUTAN TAHUN '.$tahun);
        $AKsheet->setCellValue("L$a", 'NILAI BUKU TAHUN '.$tahun);

        $AKsheet->getStyle("A$a:L$a")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("A$a:L$a")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $a = 6;
        
        foreach($asset as $item){
            $AKsheet->setCellValue("A$a", ($a-5));
            $spreadsheet->getActiveSheet()->getStyle("A$a:C$a")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $AKsheet->setCellValue("B$a", $item->nm_asset);
            $AKsheet->setCellValue("C$a", $item->number);
            $AKsheet->setCellValue("D$a", $item->price);
            $AKsheet->setCellValue("E$a", $item->jumlah_penyusutan_seb);
            $AKsheet->setCellValue("F$a", $item->nilai_buku_seb);
            $spreadsheet->getActiveSheet()->getStyle("G$a:L$a")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $AKsheet->getStyle("D:F")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $AKsheet->setCellValue("G$a", $item->golongan->metode_penyusutan);
            $AKsheet->setCellValue("H$a", $item->golongan->nm_golongan);
            $AKsheet->setCellValue("I$a", $item->golongan->rate."%");
            @$AKsheet->setCellValue("J$a", AppHelper::tgl_indo($item->penyusutan[count($item->penyusutan)-1]['tanggal']));
            $AKsheet->setCellValue("K$a", $item->jumlah_penyusutan);
            $AKsheet->setCellValue("L$a", $item->nilai_buku);
            
            $AKsheet->getStyle("K$a:L$a")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $a++;
            }
            
            
            $spreadsheet->getActiveSheet()->mergeCells("A$a:C$a");

            $AKsheet->getStyle("A$a:L$a")->getFont()->setBold(true);
            
            $AKsheet->setCellValue("A$a", 'TOTAL');
            $AKsheet->setCellValue("D$a", "=sum(D6:D$a)");
            $AKsheet->setCellValue("E$a", "=sum(E6:E$a)");
            $AKsheet->setCellValue("F$a", "=sum(F6:F$a)");
            $AKsheet->setCellValue("K$a", "=sum(K6:K$a)");
            $AKsheet->setCellValue("L$a", "=sum(L6:L$a)");
            $spreadsheet->getActiveSheet()->getStyle("E$a:H$a")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("A$a:C$a")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $AKsheet->getStyle("D$a")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $AKsheet->getStyle("K$a:L$a")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            

        $spreadsheet->getActiveSheet()->getStyle("A4:L" . ($a))->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000000'));



            
            
        // return $modal;



        // return $total_modal + ($debit - $kredit);


        // return response()->json(["data" => $total_modal]);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="LAPORAN KEUANGAN PERIODE Tahun ' . ($tahun) . '.xlsx"');
        $writer->save('php://output');
    }

}
