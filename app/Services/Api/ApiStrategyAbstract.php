<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class ApiStrategyAbstract
{
    /**
     * Fetch data using the strategy's specific API call.
     *
     * @return mixed
     */
    abstract public function fetchData();

    /**
     * Provide the validation rules for the API response.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Validate the API response data.
     *
     * @param array $data
     * @throws ValidationException
     */
    protected function validateResponse(array $data)
    {
        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}