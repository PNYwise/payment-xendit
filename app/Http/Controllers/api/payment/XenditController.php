<?php

namespace App\Http\Controllers\api\payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Xendit\Xendit;

class XenditController extends Controller
{
    private $token = 'xnd_development_0PDbEJb2mkNKM15d5PkbtaseGcBYa6YWkPJESpav9c0Pvzm367GynOmIaZ3azaM';


    public function getListVA()
    {
        Xendit::setApiKey($this->token);
        $getVABanks = \Xendit\VirtualAccounts::getVABanks();

        return response()->json($getVABanks, 200);
    }

    public function createVA(Request $request)
    {
        Xendit::setApiKey($this->token);

        $externalId = "VA-" . time();
        $params = [
            "external_id" => $externalId,
            "bank_code" => $request->bank_code,
            "name" => $request->name,
            "email" => $request->email,
            "excepted_amount" => $request->excepted_amount,
            "expiration_date" => Carbon::now()->addMinute(1)->toISOString(),
            "is_single_use" => true
        ];

        Payment::create([
            'external_id' => $externalId,
            "email" => $request->email,
            'payment_channel' => 'Virtual Account',
            'excepted_amount' => $request->excepted_amount,
            'status' => 0,
        ]);

        $createVA = \Xendit\VirtualAccounts::create($params);
        return response()->json($createVA, 200);
    }


    public function callbackVA(Request $request)
    {
        $external_id = $request->external_id;
        $status = $request->status;

        $payment = Payment::where('external_id', $external_id)->exists();

        if ($payment) {
            $update = Payment::where('external_id', $external_id)->update([
                'status' => 1
            ]);
            if ($update > 0) {
                return 'ok';
            }
            return 'false';
        } else {
            return response()->json('data not found', 400);
        }
        return response()->json("tokens not valid", 400);
    }


    public function getListDisbursements()
    {
        Xendit::setApiKey($this->token);
        $getDisbursementsBanks = \Xendit\Disbursements::getAvailableBanks();
        return response()->json($getDisbursementsBanks, 200);
    }

    public function createDisbursement(Request $request)
    {
        Xendit::setApiKey($this->token);

        $externalId = 'disb_test_success-' . time();
        // "external_id": "disb_test_success-1641781818",
        // "amount": 90000,
        // "bank_code": "BCA",
        // "account_holder_name": "Joe",
        // "account_number": "1234567890",
        // "description":"Test - Successful disbursement"
        $params = [
            'external_id' => 'disb_test_success-1641781818',
            'amount' => $request->amount,
            'bank_code' => $request->bank_code,
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            // 'X-IDEMPOTENCY-KEY' => time(),
            "description" => "Test - Successful disbursement"
        ];
        // return $params;

        $createDisbursement = \Xendit\Disbursements::create($params);
        return response()->json($createDisbursement, 200);
    }

    public function callbackDisbursement(Request $request)
    {
        $external_id = $request->external_id;
        $status = $request->status;

        $payment = Payment::where('external_id', $external_id)->exists();

        if ($payment) {
            $update = Payment::where('external_id', $external_id)->update([
                'status' => 1
            ]);
            if ($update > 0) {
                return 'ok';
            }
            return 'false';
        } else {
            return response()->json('data not found', 400);
        }
        return response()->json("tokens not valid", 400);
    }
}
