<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Exports\RecordParkingExport;
use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\Admin\RecordParking\ARecordParkingInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminRecordParkingController extends Controller
{
    private $aRecordParkingInterface;

    public function __construct(ARecordParkingInterface $aRecordParkingInterface)
    {
        $this->aRecordParkingInterface = $aRecordParkingInterface;
    }

    public function index(Request $request)
    {
        $data = $this->aRecordParkingInterface->index($request);

        if ($data['typeInterface'] == "datatables") {
            return $data['dataInterface'];
        }
        else {
            return view('dashboard.contents.content', $data);
        }

    }

    public function exportExcel(Request $request)
    {
        try {
            $service = $this->aRecordParkingInterface->exportExcel($request);

            if ($service['statusInterface'] == "failed") {
                return $this->errorvalidator($service['messageInterface']);
            } else {
                ob_end_clean();
                return Excel::download(new RecordParkingExport($service['dataInterface']->dataQuery), 'Data Record Parking.xlsx');
            }
        }
        catch (\Throwable $th) {
            return $this->errorCode();
        }
    }

}
