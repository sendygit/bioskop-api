<?php

namespace App\Http\Controllers;

use App\Models\Ttiket;
use App\Models\Bills;
use App\Models\Tsnacks;
use App\Models\TdetailSnacks;
use App\Models\TdetailTiket;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
                'success' => true,
                'message' => 'Tiket Berhasil disubmit',
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
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
                'success' => true,
                'message' => 'Snack Berhasil disubmit',
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
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
                'id_status_pembayaran' => 3,
                'id_metode_pembayaran' => $request->id_metode_pembayaran,
                'created_by' => $request->user()->id_user,
                'updated_by' => $request->user()->id_user
            ]);
            $response = [
                'success' => true,
                'message' => 'Bills Berhasil disubmit',
                'data' => $data
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed" . $e->errorInfo
            ]);
            
        }
    }

    //Verifikasi Pembayaran
    public function verifPembayaran(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_bills' => ['required'],
            'images' => [
                'image' => ['required']
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // if ($request->image && $request->image->isValid()) {
           
        //     $image = uniqid().'.'.$request->image->extension();
        //     $request->image->move(public_path('images/verif'),$image);
        //     $path = "public/images/verif/$image";
    
        //     $data = DB::table('t_verification_bills')->insert([
        //         'id_bills' => $request->id_bills,
        //         'image' => $path,
        //         'created_by' => $request->user()->id_user,
        //         'updated_by' => $request->user()->id_user,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]);

        //     $response = [
        //         'success' => true,
        //         'message' => 'Bukti Telah Terkirim',
        //         'data' => $data
        //     ];
        //     return response()->json($response, Response::HTTP_CREATED);

        //     } else {
        //     $response = [
        //         'success' => false,
        //         'message' => 'Failed',
        //         'data' => ''
        //     ];
        //     return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        //MULTIPLE   
        $files = $request->file('images');
        if ($request->hasFile('images')) {

            foreach ($files as $file) {
                $new_name = uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('images/verif'),$new_name);
                $path = "public/images/verif/$new_name";

                $data = DB::table('t_verification_bills')->insert([
                        'id_bills' => $request->id_bills,
                        'image' => $path,
                        'created_by' => $request->user()->id_user,
                        'updated_by' => $request->user()->id_user,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
            }
            $response = [
                'success' => true,
                'message' => 'Bukti Telah Terkirim'
            ];

            return response()->json($response, Response::HTTP_CREATED);
         } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed',
                    'data' => ''
                ];
                return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
       }
    }


    public function historyTransaksi(Request $request)
    {
        $id_user = $request->user()->id_user;
        $id_status_pembayaran = $request->id_status_pembayaran;
        $start_date = ($request->start_date) ? date($request->start_date) : date('y-m-d', strtotime("-30days"));
        $end_date = ($request->end_date) ? Carbon::parse($request->end_date)->addDays(1)->format('y-m-d') : date('y-m-d', strtotime("+7days"));
      
        $data = DB::table('t_bills')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->when($id_user, function($query, $id_user){
                return $query->where('id_user', $id_user);
            })
            ->when($id_status_pembayaran, function($query, $id_status_pembayaran){
                return $query->where('id_status_pembayaran', $id_status_pembayaran);
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        $response = [
            'success' => true,
            'message' => 'History Trasaksi',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }


    public function detailTransaksi(Request $request)
    {
        try{
        $data = Bills::findOrFail($request->id_bills);
        $response = [
            'success' => true,
            'message' => 'Detail transaksi',
            'data' => $data
        ];
   
         return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed"
            ]);
    }
    }
   
    
}
