<?php

namespace App\Http\Requests\PPIC\ProductionPlan;

use App\Enum\PlanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class UpdateProductionPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quantity' => 'integer|min:1',
            'product_id' => 'integer|exists:products,id',
            'notes' => 'string',
            'status' => new In([
                PlanStatus::CREATED->value,
                PlanStatus::NEEDS_APPROVAL->value
            ])
        ];
    }
}
