<?php
namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ValidateOneYearFromToday;


class TripRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (strtoupper($this->method()))

        {

            case "DELETE":
                return [];
            case "GET":
                return [
                    'departure_from' => 'required|exists:airports,code',
                    'departure_date' => 'required|date_format:Y-m-d|after:today|before:1 year',
                    'arrival_to' => 'required|exists:airports,code',
                    'return_date' => 'required_without:one_way|required_if:one_way,false',

                ];
            case "POST":
                return [
                    'departure_flight_id' => 'required',
                    'return_flight_id' => 'sometimes',
                ];

        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
