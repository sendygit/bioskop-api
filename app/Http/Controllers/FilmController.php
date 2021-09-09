<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\ProductionHouse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class FilmController extends Controller
{
    public function index()
    {
        $data = Film::orderBy('id_film', 'ASC')->get();
        $response = [
            'message' => 'List Film',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function listFilm(Request $request)
    {
        $data = DB::table('m_film')
                ->where('id_ph', $request->id_ph)
                ->get();
        $response = [
            'message' => 'List film by Genre and Production house',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }






































}
