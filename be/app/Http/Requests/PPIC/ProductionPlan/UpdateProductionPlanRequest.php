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
        $plan = $this->route('production_plan');

        if ($plan && !in_array($plan->status, [
            PlanStatus::CREATED,
            PlanStatus::NEEDS_APPROVAL,
            PlanStatus::DECLINED
        ])) {
            return false;
        }

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
            'quantity' => 'nullable|integer|min:1',
            'product_id' => 'nullable|integer|exists:products,id',
            'notes' => 'nullable|string',
            'status' => [
                'nullable',
                new In([
                    PlanStatus::CREATED->value,
                    PlanStatus::NEEDS_APPROVAL->value
                ])
            ]
        ];
    }
}
