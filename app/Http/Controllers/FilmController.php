<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\ProductionHouse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class FilmController extends Controller
{
    public function listfilm(Request $request)
    {
        $id_genre = $request->id_genre;
        $id_ph = $request->id_ph;
      
        return \DB::table('vw_film')
            ->orderBy('id_film', 'ASC')
            ->when($id_genre, function($query, $id_genre){
                return $query->where('id_genre', $id_genre);
            })
            ->when($id_ph, function($query, $id_ph){
                return $query->where('id_ph', $id_ph);
            })
            ->get();
    }

    public function listGenre()
    {
        $data = Genre::orderBy('id_genre', 'ASC')->get();
        $response = [
            'message' => 'List genre',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function detailFilm(Request $request)
    {
        $data = Film::findOrFail($request->id_film);
        $response = [
            'message' => 'Detail film',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }

  
    public function ph(Request $request)
    {
        $id_ph = $request->id_ph;
      
        return \DB::table('production_house')
            ->orderBy('id_ph', 'ASC')
            ->when($id_ph, function($query, $id_ph){
                return $query->where('id_ph', $id_ph);
            })->get();
    }

    public function schedule(Request $request)
    {
        $id_film = $request->id_film;
        $id_waktu_tayang = $request->id_waktu_tayang;
      
        return \DB::table('vw_schedule')
            ->orderBy('id_schedule', 'ASC')
            ->when($id_film, function($query, $id_film){
                return $query->where('id_film', $id_film);
            })
            ->when($id_waktu_tayang, function($query, $id_waktu_tayang){
                return $query->where('id_waktu_tayang', $id_waktu_tayang);
            })
            ->get();
    }

    //KURSI
    public function getAvailableSeat(Request $request)
    {
        $id_schedule = $request->id_schedule;
        return DB::table('kursi')
            ->join('vw_kursi_terjual', function ($join,  $id_schedule) {
                $join->on('kursi.id_kursi', '=', 'vw_kursi_terjual.id_kursi')
                ->whereNotNull('vw_kursi_terjual.id_schedule', '=', $id_schedule);
            })
            ->get();
    }







}
