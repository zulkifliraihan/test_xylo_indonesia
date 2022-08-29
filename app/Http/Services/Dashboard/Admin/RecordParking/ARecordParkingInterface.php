<?php

namespace App\Http\Services\Dashboard\Admin\RecordParking;

interface ARecordParkingInterface {
    public function index($request);
    public function exportExcel($request) : array;
}
