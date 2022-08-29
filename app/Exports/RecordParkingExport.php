<?php

namespace App\Exports;

use App\Models\RecordParking;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;



class RecordParkingExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;

    private $i = 1;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $dataQuery = $this->data;

        $recordParking = RecordParking::where(function ($query) use ($dataQuery) {
            foreach ($dataQuery as $key => $value) {
                $query->whereRaw($value);
            }
        })->get();

        $dataCollection = [];

        $setValueNull = "Car not out from Parking!";
        foreach ($recordParking as $value) {
            $item = [];
            $item['status_code'] = $value->status == 0 ? "'0" : $value->status;
            $item['status_name'] = $value->status == 0 ? "Not Completed" : "Completed";
            $item['nopol'] = $value->nopol;
            $item['code_park'] = $value->code_park;
            $item['timein'] = $value->timein;
            $item['timeout'] = $value->timeout != null ? $value->timeout : $setValueNull;
            $item['total_bayar'] = $value->total_bayar != null ? 'Rp. ' . number_format($value->total_bayar) : "Rp. 0";
            $item['paymentmethod'] = $value->paymentMethod != null ? $value->paymentMethod->name : $setValueNull;

            array_push($dataCollection, $item);
        }

        return collect($dataCollection);

    }

    public function map($data): array
    {
        return [
            $this->i++,
            $data['status_code'],
            $data['status_name'],
            $data['nopol'],
            $data['code_park'],
            $data['timein'],
            $data['timeout'],
            $data['total_bayar'],
            $data['paymentmethod']
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Status Code',
            'Status Name',
            'Nomor Polisi Kendaran',
            'Code Parking',
            'Time In',
            'Time Out',
            'Total Payment',
            'Payment Method',
        ];
    }

    public function registerEvents(): array
    {
        return array(
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setAutoFilter('A1:' . $event->sheet->getDelegate()->getHighestColumn() . '1');
            }
        );
    }
}
