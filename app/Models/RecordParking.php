<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordParking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusColorAttribute(): ?string
    {
        if ($this->status == 0) {
            return 'danger';
        }
        elseif ($this->status == 1) {
            return 'success';
        }
    }

    public function getStatusTextAttribute(): ?string
    {
        if ($this->status == 0) {
            return 'Not Completed';
        }
        elseif ($this->status == 1) {
            return 'Completed';
        }
        return 'Terjadi Kesalahan, Hubungi Admin!';
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id' , 'paymentmethod_id');

    }
}
