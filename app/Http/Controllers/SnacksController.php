<?php

namespace App\Http\Controllers;

use App\Models\Snacks;
use App\Models\KategoriSnacks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SnacksController extends Controller
{
    public function listKategori()
    {
        $data = KategoriSnacks::orderBy('id_kategori', 'ASC')->get();
        $response = [
            'message' => 'List kategori snack',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }


    public function indexSnacks()
    {
        $data = Snacks::orderBy('id_snacks', 'ASC')->get();
        $response = [
            'message' => 'List Snack',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function listSnacks(Request $request)
    {
        $data = DB::table('m_snacks')
                ->where('id_kategori', $request->id_kategori)
                ->get();
        $response = [
            'message' => 'List snacks by kategori',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }


































}
