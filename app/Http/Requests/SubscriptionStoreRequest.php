<?php

namespace App\Http\Requests;

use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_number' => [
                'required',
                'max:16',
                'min:16',
                Rule::unique(Subscriber::class),
            ],
            'card_owner' => 'required',
            'expire_year' => [
                'required',
                'integer',
                'digits:4',
                'min:' . now()->format('Y'),
            ],
            'expire_month' => [
                'required',
                'integer',
                'digits_between:1,2',
                'max:12',
                function (string $attr, mixed $value, \Closure $fail) {
                    if ($this->get('expire_year') == now()->format('Y')) {
                        if ($this->get('expire_month') < now()->format('m')) {
                            $fail(
                                trans(
                                    'validation.min.numeric',
                                    ['attribute' => $attr, 'min' => now()->format('m')]
                                )
                            );
                        }
                    }
                }
            ],
            'cvv' => 'required|max:3|min:3',
            'x_ip' => 'required|ip|ipv4',
            'discount_percent' => 'required|integer|max:100|min:0',
            'quantity' => 'required|integer|min:1',
            'force_3ds' => 'required|bool',
            'use_wallet' => 'required|bool',
            'language' => 'required|max:2|min:2',
        ];
    }
}
