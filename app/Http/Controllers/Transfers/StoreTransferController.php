<?php

namespace App\Http\Controllers\Transfers;

use App\Actions\Transfers\CreateTransfer;
use App\Actions\Transfers\FindDefaultWalletFromAccountToTransfer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transfers\StoreTransferRequest;
use App\Models\Wallet;

class StoreTransferController extends Controller
{
    public function __invoke(StoreTransferRequest $request)
    {
        $data = $request->validated();

        $defaultDestinationWallet = (new FindDefaultWalletFromAccountToTransfer)->execute($data['destination_account_number']);
        $sourceWallet = Wallet::findOrFail($data['source_wallet_id']);

        $transfer = (new CreateTransfer)->execute($sourceWallet, $defaultDestinationWallet, $data['amount']);

        return response()->json([
            'message' => 'Transfer created successfully!',
            'data' => $transfer,
        ], 201);
    }
}
