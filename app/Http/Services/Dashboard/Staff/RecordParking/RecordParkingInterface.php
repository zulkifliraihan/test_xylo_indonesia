<?php

namespace App\Http\Services\Dashboard\Staff\RecordParking;

interface RecordParkingInterface {
    public function index();
    public function enterPark($data) : array;
    public function outPark($data);
    public function payment($data) : array;
}
