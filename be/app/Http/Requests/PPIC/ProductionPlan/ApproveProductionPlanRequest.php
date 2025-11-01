<?php

namespace App\Http\Requests\PPIC\ProductionPlan;

use App\Enum\PlanStatus;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;

class ApproveProductionPlanRequest extends FormRequest
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
        $currentPlan = $this->route('productionPlan');
        $currentStatus = $currentPlan?->status;

        return [
            'status' => [
                'required',
                new In([
                    PlanStatus::APPROVED->value,
                    PlanStatus::DECLINED->value
                ]),
                function (
                    string $attribute,
                    string $value,
                    Closure $fail
                ) use ($currentStatus) {
                    if (!$currentStatus) {
                        return;
                    }

                    $newStatus = PlanStatus::tryFrom($value);
                    if ($newStatus == $currentStatus) {
                        $fail("Plan is already in {$currentStatus->value}");
                        return;
                    }

                    $allowedTransitions = match ($currentStatus) {
                        PlanStatus::NEEDS_APPROVAL => [
                            PlanStatus::APPROVED,
                            PlanStatus::DECLINED
                        ],
                        default => [],
                    };

                    if (!in_array($newStatus, $allowedTransitions)) {
                        $fail("Can't change {$currentStatus->value} to {$newStatus->value}");
                    }
                }
            ],
            'deadline' => [
                'date',
                'date_format:Y-m-d',
                new RequiredIf(function () {
                    return $this->input('status') === PlanStatus::APPROVED->value;
                }),
                new ProhibitedIf(function () {
                    return $this->input('status') === PlanStatus::DECLINED->value;
                })
            ],
        ];
    }
}
