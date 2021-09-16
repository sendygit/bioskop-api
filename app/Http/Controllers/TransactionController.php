<?php

namespace App\Http\Controllers;

use App\Models\Ttiket;
use App\Models\Bills;
use App\Models\Tsnacks;
use App\Models\TdetailSnacks;
use App\Models\TdetailTiket;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function submitTiket(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_schedule' => ['required'],
            'id_user' => ['required', ],
            'details' => [
                'id_kursi' => ['required'],
                'harga' => ['required']
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $tiket = Ttiket::create([
                'id_schedule' => $request->id_schedule,
                'id_user' => $request->id_user,
                'created_by' => $request->user()->id_user,
                'updated_by' => $request->user()->id_user
            ]);

            foreach ($request->details as $item) {
                $detail = TdetailTiket::create([
                    'id_kursi' => $item['id_kursi'],
                    'harga' => $item['harga'],
                    'id_t_tiket' => $tiket->id,
                    'created_by' => $request->user()->id_user,
                    'updated_by' => $request->user()->id_user
                ]);
            }
            
            $response = [
                'message' => 'Success',
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
            
        }
    }

    public function submitSnacks(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_user' => ['required'],
            'details' => [
                'id_snacks' => ['required'],
                'jumlah' => ['required'],
                'harga_snacks' => ['required']
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $snacks = Tsnacks::create([
                'id_user' => $request->id_user,
                'created_by' => $request->user()->id_user,
                'updated_by' => $request->user()->id_user
            ]);

            foreach ($request->details as $item) {
                $detail = TdetailSnacks::create([
                    'id_snacks' => $item['id_snacks'],
                    'id_t_snack' => $snacks->id,
                    'jumlah' => $item['jumlah'],
                    'harga_snacks' => $item['harga_snacks'],
                    'created_by' => $request->user()->id_user,
                    'updated_by' => $request->user()->id_user
                ]);
            }
            
            $response = [
                'message' => 'Success',
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
            
        }
    }

    public function buatPesanan(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_t_tiket' => ['required'],
            'id_t_snacks' => ['required'],
            'id_user' => ['required'],
            'id_status_pembayaran' => ['required',],
            'id_metode_pembayaran' => ['required',]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = Bills::create([
                'id_t_tiket' => $request->id_t_tiket,
                'id_t_snacks' => $request->id_t_snacks,
                'id_user' => $request->id_user,
                'id_status_pembayaran' => $request->id_status_pembayaran,
                'id_metode_pembayaran' => $request->id_metode_pembayaran,
                'created_by' => $request->user()->id_user,
                'updated_by' => $request->user()->id_user
            ]);
            $response = [
                'message' => 'Success',
                'data' => $data
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
            
        }
    }


    public function verifPembayaran(Request $request)
    {
        return $request->file('image')->store('post-images');
    }


    public function historyTransaksi(Request $request)
    {
        $id_user = $request->id_user;
        $id_status_pembayaran = $request->id_status_pembayaran;
        $start_date = ($request->start_date) ? $request->start_date : date('y-m-d', strtotime("-30days"));
        $end_date = ($request->end_date) ? $request->end_date : date('y-m-d', strtotime("+7days"));

        return \DB::table('t_bills')
            ->whereBetween('waktu_cetak', [$start_date, $end_date])
            ->when($id_user, function($query, $id_user){
                return $query->where('id_user', $id_user);
            })
            ->when($id_status_pembayaran, function($query, $id_status_pembayaran){
                return $query->where('id_status_pembayaran', $id_status_pembayaran);
            })
            ->orderBy('waktu_cetak', 'DESC')
            ->get();
    }


    public function detailTransaksi(Request $request)
    {
        $data = Bills::findOrFail($request->id_bills);
        $response = [
            'message' => 'Detail transaksi',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }
   
    
}
