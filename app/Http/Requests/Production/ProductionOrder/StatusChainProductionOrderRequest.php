<?php

namespace App\Http\Requests\Production\ProductionOrder;

use App\Enum\OrderStatus;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Validator;

class StatusChainProductionOrderRequest extends FormRequest
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
        $currentOrder = $this->route('productionOrder');
        $currentStatus = $currentOrder?->status;
        $quantityPlanned = $currentOrder?->quantity_planned ?? 0;

        return [
            'quantity_actual' => [
                'integer',
                'min:0',
                "max:{$quantityPlanned}",
                new RequiredIf(function () {
                    return $this->input('status') == OrderStatus::COMPLETED->value;
                })
            ],
            'quantity_rejected' => [
                'integer',
                'min:0',
                "max:{$quantityPlanned}",
                new RequiredIf(function () {
                    return $this->input('status') == OrderStatus::COMPLETED->value;
                })
            ],
            'status' => [
                'required',
                new Enum(OrderStatus::class),
                function (
                    string $attribute,
                    string $value,
                    Closure $fail
                ) use ($currentStatus) {
                    if (!$currentStatus) {
                        return;
                    }

                    $newStatus = OrderStatus::tryFrom($value);
                    if ($newStatus == $currentStatus) {
                        $fail("Order is already in {$currentStatus->value}");
                        return;
                    }

                    $allowedTransitions = match ($currentStatus) {
                        OrderStatus::WAITING => [OrderStatus::IN_PROGRESS],
                        OrderStatus::IN_PROGRESS => [
                            OrderStatus::COMPLETED,
                            OrderStatus::CANCELED
                        ],
                        OrderStatus::COMPLETED, OrderStatus::CANCELED => [],
                        default => [],
                    };

                    if (!in_array($newStatus, $allowedTransitions)) {
                        $fail("Can't change {$currentStatus->value} to {$newStatus->value}");
                    }
                }
            ],
            'notes' => 'max:500|string'
        ];
    }
}
