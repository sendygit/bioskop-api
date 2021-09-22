<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\StatusPembayaran;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function paymentMethod()
    {
        $data = MetodePembayaran::orderBy('id_metode_pembayaran', 'ASC')->get();
        $response = [
            'success' => true,
            'message' => 'List metode pembayaran',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function paymentStatus()
    {
        $data = StatusPembayaran::orderBy('id_status_pembayaran', 'ASC')->get();
        $response = [
            'success' => true,
            'message' => 'List status pembayaran',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
