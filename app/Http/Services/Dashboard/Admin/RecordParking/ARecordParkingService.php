<?php

namespace App\Http\Services\Dashboard\Admin\RecordParking;

use App\Models\PaymentMethod;
use App\Models\RecordParking;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;

class ARecordParkingService implements ARecordParkingInterface {

    private $recordParking;
    private $dateNow;

    public function __construct(RecordParking $recordParking)
    {
        $this->recordParking = $recordParking;
        $this->dateNow = Carbon::now()->format('Y-m-d H:i:s');
    }

    public function index($request)
    {
        $dataReturn = [];

        $paymentMethods = PaymentMethod::all();

        $recordParking = RecordParking::with('paymentMethod');

        if (request()->ajax()) {
            $dataReturn['typeInterface'] = 'datatables';

            $responseDatatables = DataTables()->eloquent($recordParking)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {

                    if (!is_null($request->get('status') )) {
                        $status = (int) $request->get('status');

                        $query->where('status', $status);
                    }

                    if (!empty($request->get('from_date') )) {
                        $fromDate = $request->get('from_date');

                        if ($fromDate != -1) {
                            $query->whereDate('timein', '>=' ,$fromDate);
                        }
                    }

                    if (!empty($request->get('from_date')) && !empty($request->get('to_date'))) {
                        $fromDate = $request->get('from_date');
                        $toDate = $request->get('to_date');

                        if ($toDate != -1) {
                            $query->whereDate('timein', '>=' , $fromDate)
                                  ->whereDate('timein', '<=', $toDate);
                        }
                    }

                    if (!empty($request->search['value'])) {
                        $valueSearch = $request->search['value'];

                        if ($valueSearch != -1) {
                            if (strcasecmp("Embed", $valueSearch) == 0) {
                                $valueSearch = 2;
                            }
                            elseif (strcasecmp("Non Embed", $valueSearch) == 0) {
                                $valueSearch = 2;
                            }

                            $query->whereHas('layanan', function($queryMultiple) use ($valueSearch) {
                                $queryMultiple->where('nama', 'like', '%' . $valueSearch . '%')
                                ->orWhere('deskripsi_singkat', 'like', '%' . $valueSearch . '%')
                                ->orWhere('layanan_type_id', 'like', '%' . $valueSearch . '%');
                            });

                        }
                    }

                })
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

    public function exportExcel($request) : array
    {
        $dataReturn = [];

        if ($request->toDate != null && $request->fromDate == null) {
            $dataReturn['statusInterface'] = 'failed';
            $dataReturn['messageInterface'] = 'Filter from date not selected! Choose the date!';
            return $dataReturn;
        }

        $dataQuery = [];

        if ($request->filled('fromDate')) {
            $fromDate = $request->fromDate;
            $query = "timein >= '$fromDate'";
            array_push($dataQuery, $query);
        }
        if ($request->filled('toDate')) {
            $toDate = $request->toDate;
            $fromDate = $request->fromDate;
            $query = "timein >= '$fromDate' AND timein <= '$toDate'";
            array_push($dataQuery, $query);
        }
        if ($request->filled('status')) {
            $status = $request->status;
            $query = "status = '$status'";
            array_push($dataQuery, $query);
        }

        $dataReturn['statusInterface'] = 'success';
        $dataReturn['typeInterface'] = 'bool';

        $responseService = (object) [
            'response' => 'downloaded',
            'dataQuery' => $dataQuery
        ];

        $dataReturn['dataInterface'] = $responseService;

        return $dataReturn;

    }


}
