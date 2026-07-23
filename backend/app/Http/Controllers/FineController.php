<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Services\DarajaPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FineController extends Controller
{
    protected DarajaPaymentService $darajaService;

    public function __construct(DarajaPaymentService $darajaService)
    {
        $this->darajaService = $darajaService;
    }

    public function index(Request $request): JsonResponse
    {
        $fines = Fine::with('member.user', 'loan.bookCopy.book')->latest()->paginate(15);
        return response()->json($fines);
    }

    public function payWithDaraja(Request $request, Fine $fine): JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        $stkResponse = $this->darajaService->initiateStkPush($fine, $validated['phone_number'], $validated['amount']);

        return response()->json($stkResponse);
    }

    public function darajaCallback(Request $request): JsonResponse
    {
        $data = $request->all();
        $result = $this->darajaService->processCallback($data);

        if ($result['status'] === 'success') {
            // Find fine and update
            $fineId = $request->input('fine_id', 1);
            $fine = Fine::find($fineId);
            if ($fine) {
                $fine->update([
                    'balance' => 0,
                    'status' => 'paid',
                    'transaction_reference' => $result['receipt'],
                ]);
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function waive(Fine $fine): JsonResponse
    {
        $fine->update([
            'balance' => 0,
            'status' => 'waived',
        ]);

        return response()->json(['message' => 'Fine waived successfully', 'fine' => $fine]);
    }
}
