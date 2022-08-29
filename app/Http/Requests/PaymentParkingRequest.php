<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentParkingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nopol' => 'required',
            'code_park' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
            'total_bayar' => 'required',
            'paymentmethod_id' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "status" => 'failed',
            "response" => "failed-validation",
            "message" => "Error! The request not expected!",
            "errors" => $validator->errors()->first()
        ], 422));
    }
}