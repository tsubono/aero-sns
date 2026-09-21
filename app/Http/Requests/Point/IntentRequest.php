<?php

namespace App\Http\Requests\Point;

use Illuminate\Foundation\Http\FormRequest;

class IntentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validPlanIds = collect(config('points.plans'))->pluck('id')->toArray();

        return [
            'plan_id' => ['required', 'integer', 'in:' . implode(',', $validPlanIds)],
        ];
    }
}
