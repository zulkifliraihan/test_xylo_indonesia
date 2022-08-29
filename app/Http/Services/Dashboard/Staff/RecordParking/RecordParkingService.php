<?php

namespace App\Http\Services\Dashboard\Staff\RecordParking;

use App\Models\PaymentMethod;
use App\Models\RecordParking;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;

class RecordParkingService implements RecordParkingInterface {

    private $recordParking;
    private $dateNow;

    public function __construct(RecordParking $recordParking)
    {
        $this->recordParking = $recordParking;
        $this->dateNow = Carbon::now()->format('Y-m-d H:i:s');
    }

    public function index()
    {
        $dataReturn = [];

        $paymentMethods = PaymentMethod::all();

        $recordParking = RecordParking::with('paymentMethod');

        if (request()->ajax()) {
            $dataReturn['typeInterface'] = 'datatables';

            $responseDatatables = DataTables()->eloquent($recordParking)
                ->addColumn('number', function ($number) {
                    $i = 1;
                    return $i++;
                })
                ->addColumn('status', function ($query) {
                    $status = '<span class="badge badge-'. $query->status_color .'">'. $query->status_text .'</span>';

                    return $status;
                })
                ->addColumn('jam_masuk', function ($query) {
                    $jamMasuk = Carbon::parse($query->timein)->format('d M Y, H:m');
                    return $jamMasuk;
                })
                ->addColumn('jam_keluar', function ($query) {
                    if ($query->timeout != null) {
                        $jamKeluar = Carbon::parse($query->timeout)->format('d M Y, H:m');
                    }
                    else {
                        $jamKeluar = "Car not out from Parking!";
                    }
                    return $jamKeluar;
                })
                ->addColumn('total', function ($query) {
                    if ($query->total_bayar != null) {
                        $total = 'Rp. ' . number_format($query->total_bayar,0,',','.');;
                    }
                    else {
                        $total = "Rp. 0";
                    }
                    return $total;
                })
                ->addColumn('paymentmethod', function ($query) {
                    if ($query->paymentMethod != null) {
                        $paymentMethod = $query->paymentMethod->name;
                    }
                    else {
                        $paymentMethod = "Car not out from Parking!";
                    }

                    return $paymentMethod;

                })
                ->rawColumns([
                        'number',
                        'jam_masuk',
                        'jam_keluar',
                        'status',
                        'total',
                        'paymentmethod'
                ])
                ->addIndexColumn()
                ->make(true);

            $dataReturn['dataInterface'] = $responseDatatables;

            return $dataReturn;
        }

        $dataReturn['typeInterface'] = 'array';
        $dataReturn['paymentMethods'] = $paymentMethods;

        return $dataReturn;

    }

    public function enterPark($data) : array
    {
        $dataReturn = [];

        $nopol = strtoupper(str_replace(" ",  "", $data['nopol']));

        $searchNopol = $this->recordParking->where('nopol', $nopol)->first();

        if ($searchNopol != null && $searchNopol->status == 1) {
            $dataReturn['statusInterface'] = 'failed';
            $dataReturn['messageInterface'] = 'Nomor Polisi Kendaraan is exist!';
            return $dataReturn;
        }

        $code = "PRK";

        $dateNow = Carbon::now()->format('Ymdhi');
        $randomString = strtoupper(Str::random(3));

        $codePark = $code . '-' . $nopol . '-' . $randomString . '-' . $dateNow;
        $dataInsert = [
            'status' => 0,
            'code_park' => $codePark,
            'nopol' => $nopol,
            'timein' => $this->dateNow
        ];

        $dataCreate = $this->recordParking->insert($dataInsert);

        $dataReturn['statusInterface'] = 'success';
        $dataReturn['typeInterface'] = 'bool';

        $responseService = (object) [
            'response' => 'created',
        ];

        $dataReturn['dataInterface'] = $responseService;

        return $dataReturn;

    }

    public function outPark($data)
    {
        $dataReturn = [];

        if ($data['nopol'] != null && $data['code_park'] == null) {
            $nopol = strtoupper(str_replace(" ",  "", $data['nopol']));

            $dataRecordParking = $this->recordParking->where('nopol', $nopol)->first();

            if ($dataRecordParking == null) {
                $dataReturn['statusInterface'] = 'failed';
                $dataReturn['messageInterface'] = 'Nomor Polisi Kendaraan is not exist!';
                return $dataReturn;
            }
        }
        elseif ($data['nopol'] == null && $data['code_park'] != null) {
            $dataRecordParking = $this->recordParking->where('code_park', $data['code_park'])->first();

            if ($dataRecordParking == null) {
                $dataReturn['statusInterface'] = 'failed';
                $dataReturn['messageInterface'] = 'Code Parking is not exist!';
                return $dataReturn;
            }
        }
        else {
            $nopol = strtoupper(str_replace(" ",  "", $data['nopol']));

            $dataRecordParking = $this->recordParking->where([
                ['nopol', '=', $nopol],
                ['code_park', '=', $data['code_park']]
            ])->first();

            if ($dataRecordParking == null) {
                $dataReturn['statusInterface'] = 'failed';
                $dataReturn['messageInterface'] = 'Code Parking and Nomor Polisi Kendaraan is not exist!';
                return $dataReturn;
            }

        }

        if ($dataRecordParking->status == 1) {
            $dataReturn['statusInterface'] = 'failed';
            $dataReturn['messageInterface'] = 'Data Parking is already pay for the parking!';
            return $dataReturn;
        }

        $timeOut = new DateTime($this->dateNow);
        $timmeIN = new DateTime($dataRecordParking->timein);

        $diffTime = $timmeIN->diff($timeOut);

        $totalHoursPark = $diffTime->h;
        $totalHoursPark = $totalHoursPark + ($diffTime->days*24);
        if ($diffTime->i != 0) {
            $totalHoursPark = $totalHoursPark + 1;
        }

        $totalForPay = 3000 * $totalHoursPark;

        $paymentMethods = PaymentMethod::all();

        $dataReturn['statusInterface'] = 'success';

        $responseService = (object) [
            'response' => 'searched',
            'nopol' => $data['nopol'],
            'code_park' => $dataRecordParking->code_park,
            'totalHoursPark' => $totalHoursPark,
            'totalForPay' => $totalForPay,
            'totalForPayFormat' => 'Rp. ' . number_format($totalForPay,0,',','.'),
            'timeIn' => $dataRecordParking->timein,
            'timeOut' => $this->dateNow,
            'paymentMethods' => $paymentMethods
        ];
        $dataReturn['dataInterface'] = $responseService;

        return $dataReturn;

    }

    public function payment($data) : array
    {
        $dataReturn = [];

        $searchNopol = $this->recordParking->where('code_park', $data['code_park'])->first();

        if ($searchNopol->status == 1) {
            $dataReturn['statusInterface'] = 'failed';
            $dataReturn['messageInterface'] = 'This Car is already checkout for parking!';
            return $dataReturn;
        }
        $searchNopol->update([
            'status' => 1,
            'timeout' => $data['time_out'],
            'total_bayar' => $data['total_bayar_nonformat'],
            'paymentmethod_id' => $data['paymentmethod_id'],
        ]);

        $dataReturn['statusInterface'] = 'success';
        $dataReturn['typeInterface'] = 'bool';

        $responseService = (object) [
            'response' => 'updated',
        ];

        $dataReturn['dataInterface'] = $responseService;

        return $dataReturn;

    }
}
