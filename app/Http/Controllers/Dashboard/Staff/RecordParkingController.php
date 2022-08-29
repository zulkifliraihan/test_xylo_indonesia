<?php

namespace App\Http\Controllers\Dashboard\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnterParkingRequest;
use App\Http\Requests\OutParkingRequest;
use App\Http\Requests\PaymentParkingRequest;
use App\Http\Services\Dashboard\Staff\RecordParking\RecordParkingInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordParkingController extends Controller
{
    private $recordParkingInterface;

    public function __construct(RecordParkingInterface $recordParkingInterface)
    {
        $this->recordParkingInterface = $recordParkingInterface;
    }

    public function index()
    {
        $data = $this->recordParkingInterface->index();

        if ($data['typeInterface'] == "datatables") {
            return $data['dataInterface'];
        }
        else {
            return view('dashboard.contents.content', $data);
        }
    }

    public function enter(EnterParkingRequest $request)
    {
        try {
            $data = $request->all();

            $service = $this->recordParkingInterface->enterPark($data);

            if ($service['statusInterface'] == "failed") {
                return $this->errorvalidator($service['messageInterface']);
            } else {
                return $this->success($service['dataInterface']->response);
            }
        }
        catch (\Throwable $th) {
            return $this->errorCode();
        }
    }

    public function out(OutParkingRequest $request)
    {
        try {
            $data = $request->all();
            $service = $this->recordParkingInterface->outPark($data);

            if ($service['statusInterface'] == "failed") {
                return $this->errorvalidator($service['messageInterface']);
            } else {
                return $this->successData($service['dataInterface']->response, $service['dataInterface']);
            }
        }
        catch (\Throwable $th) {
            return $this->errorCode();
        }
    }

    public function payment(PaymentParkingRequest $request)
    {
        try {
            $data = $request->all();

            $service = $this->recordParkingInterface->payment($data);

            if ($service['statusInterface'] == "failed") {
                return $this->errorvalidator($service['messageInterface']);
            } else {
                return $this->success($service['dataInterface']->response);
            }
        }
        catch (\Throwable $th) {
            return $this->errorCode();
        }
    }
}
