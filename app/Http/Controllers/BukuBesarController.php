<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class BukuBesarController extends Controller
{
    public function paginate(Request $request, $bulan, $tahun)
    {
        if (request()->wantsJson() && request()->ajax()) {
            // Set Request Per Page
            $per = (($request->per) ? $request->per : 10);
            $page = (($request->page) ? $request->page - 1 : 0);

            // Get User By Search And Per Page
            DB::statement(DB::raw('set @angka=0+' . $page * $per));
            $user = Account::with(['jurnal_item' => function ($q) use ($bulan, $tahun) {
                $q->whereHas('masterjurnal', function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                });
            }, 'jurnal_item.masterjurnal'])->whereHas('jurnal_item.masterjurnal', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            })->where(function ($q) use ($request) {
                $q->where('nm_account', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'asc')->paginate($per, ['*', DB::raw('@angka  := @angka  + 1 AS angka')]);
            // Add Columns
            $user->map(function ($a) use ($bulan, $tahun) {
                $a->jurnal_item = $a->jurnal_item->sortBy('masterjurnal.tanggal');

                $jurnal_item = '';
                $saldo = 0;
                foreach ($a->jurnal_item as $item) {
                    // if ($item->jurnal->type != 'umum') {
                    //     continue;
                    // }
                    $saldo += $item->debit - $item->kredit;
                    $jurnal_item .= '<tr>
                    <td>' . AppHelper::tanggal_indo($item->masterjurnal->tanggal) . '</td>
                    <td>' . $item->masterjurnal->kd_jurnal . '</td>
                    <td>' . $item->masterjurnal->type . '</td>
                    <td>' . AppHelper::rupiah($item->debit) . '</td>
                    <td>' . AppHelper::rupiah($item->kredit) . '</td>
                    <td>' . $item->keterangan . '</td>
                    <td>' . AppHelper::rupiah($saldo) . '</td>
                </tr>
                ';
                }

                $a->action = '<div class="card shadow-sm ">
                <div class="card-header collapsible cursor-pointer rotate col-12" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible' . $a->id . '" >
                    <h3 class="card-title">' . $a->nm_account . '</h3>
                    <div class="card-toolbar rotate-180">
                        <span class="svg-icon svg-icon-1">
                            ...
                        </span>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible' . $a->id . '" class="collapse">
                    <div class="card-body">
                    <table id="kt_datatable_vertical_scroll" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th>Tanggal</th>
                            <th>Kode Jurnal</th>
                            <th>Tipe</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Keterangan</th>
                            <th>Saldo</th>
                            
                        </tr>
                    </thead>
                    <tbody>' . $jurnal_item . '
                        
                
                    </tbody>
                </table>
                    </div>
                    <div class="card-footer">
                        Buku Besar Akun: ' . $a->nm_account . '
                    </div>
                </div>
            </div>';

                return $a;
            });
            return response()->json($user);
        } else {
            abort(404);
        }
    }
    public function ReportBukuBesar($bulan, $tahun)
    {

        $data = Account::with(['jurnal_item' => function ($q) use ($bulan, $tahun) {
            $q->whereHas('masterjurnal', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            });
        }, 'jurnal_item.masterjurnal'])->whereHas('jurnal_item.masterjurnal', function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        })->orderBy('id', 'asc')->get();


        $data = $data->map(function ($a) {
            $a->jurnal_item = $a->jurnal_item->sortBy('masterjurnal.tanggal');
            return $a;
        });

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Buku Besar');
        $sheet->getTabColor()->setRGB('38E54D');
        // Default Font Style
        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font'  => [
                'color' => ['rgb' => '000000'],
                'name'  => 'Arial Narrow'
            ]
        ]);

        //Header
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
        $sheet->setCellValue('A1', strtoupper("LAPORAN BUKU BESAR Bulan " . AppHelper::get_bulan($bulan) . " Tahun " . $tahun));
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);





        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(150, 'pt');
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(150, 'pt');




        $a = 3;
        $spasi = 2;

        foreach ($data as $item) {
            $sheet->setCellValue('C' . $a, $item->nm_account);
            $sheet->setCellValue('D' . $a, $item->kode_account);
             $spreadsheet->getActiveSheet()->getStyle('A' . $a . ':F' . $a)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('38E54D');

            $b = $a + 1;
            $c = $b + 1;

            $sheet->setCellValue('A' . $b, "Tanggal");
            $sheet->setCellValue('B' . $b, "Kode Jurnal");
            $sheet->setCellValue('C' . $b, "Debit");
            $sheet->setCellValue('D' . $b, "Kredit");
            $sheet->setCellValue('E' . $b, "saldo");
            $sheet->setCellValue('F' . $b, "Keterangan");
            $spreadsheet->getActiveSheet()->getStyle('A' . $b . ':F' . $b)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('38E54D');

            $saldo = 0;
            foreach ($item->jurnal_item as $citem) {
                $spreadsheet
                    ->getActiveSheet()
                    ->getStyle('A' . $b . ':F' . $c)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));
                $saldo = $saldo + $citem->debit - $citem->kredit;
                $sheet->setCellValue('A' . $c, $citem->masterjurnal->tanggal);
                $sheet->setCellValue('B' . $c, $citem->masterjurnal->kd_jurnal);
                $sheet->setCellValue('C' . $c, AppHelper::rupiah($citem->debit));
                $sheet->setCellValue('D' . $c, AppHelper::rupiah($citem->kredit));
                $sheet->setCellValue('E' . $c, AppHelper::rupiah($saldo));
                $sheet->setCellValue('F' . $c, $citem->keterangan);
                $c++;
            }
            $count = count($item->jurnal_item);
            $a = $a + $count + $spasi + 2;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:F' . $a)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="LAPORAN EXCEL BUKU BESAR PERIODE ' . AppHelper::get_bulan($bulan) . ' Tahun ' . $tahun . '.xlsx"');
        $writer->save('php://output');
    }
    
}
