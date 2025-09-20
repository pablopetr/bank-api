<?php

namespace App\Http\Requests\Transfers;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'source_wallet_id' => ['required', 'integer', 'exists:wallets,id'],
            'destination_account_number' => ['required', 'integer', 'exists:accounts,number'],
        ];
    }
}
