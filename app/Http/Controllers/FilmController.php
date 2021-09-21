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
      
        $list_film = DB::table('vw_film')
            ->when($id_genre, function($query, $id_genre){
                return $query->where('id_genre', $id_genre);
            })
            ->when($id_ph, function($query, $id_ph){
                return $query->where('id_ph', $id_ph);
            })
            ->groupBy('id_film')
            ->orderBy('id_film', 'ASC')
            ->get();

        foreach ($list_film as $film) {
            $film->list_genre = DB::table('vw_genre')->where('id_film', $film->id_film)->get();
        }
        $response = [
            'success' => true,
            'message' => 'List film',
            'data' => $list_film
        ];
        return response()->json($response, Response::HTTP_OK);
        
    }

    //List Genre
    public function listGenre()
    {
        $data = Genre::orderBy('id_genre', 'ASC')->get();
        $response = [
            'message' => 'List genre',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    //Detail Film
    public function detailFilm(Request $request)
    {
        $data = Film::findOrFail($request->id_film);
        $data->list_genre = DB::table('vw_genre')->where('id_film', $data->id_film)->get();
        $response = [
            'message' => 'Detail film',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }

    //Production House
    public function ph(Request $request)
    {
        $id_ph = $request->id_ph;
      
        $data = DB::table('production_house')
            ->orderBy('id_ph', 'ASC')
            ->when($id_ph, function($query, $id_ph){
                return $query->where('id_ph', $id_ph);
            })->get();
        $response = [
            'message' => 'List Production House',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }

    //Schedule masih eror
    public function schedule(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'id_film' => ['required'],
            'date' => ['required']
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id_film = $request->id_film;
        $date = ($request->date) ? $request->date : date('y-m-d');
        // return $id_film.$date;
        $data = DB::table('vw_schedule')
            ->when($id_film, function($query, $id_film){
                return $query->where('id_film', $id_film);
            })
            ->when($date, function($query, $date){
                return $query->where('date', $date);
            })
            ->orderBy('id_schedule', 'ASC')
            ->get();
        $response = [
            'message' => 'List schedule',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }

    //KURSI
    public function getAvailableSeat(Request $request)
    {
        $id_schedule = $request->id_schedule;
        $schedule = DB::table('m_schedule')->where('id_schedule', $id_schedule)->first();
        $data = DB::table('kursi')
            ->LeftJoin('vw_kursi_terjual', function ($join) use($id_schedule){
                $join->on('kursi.id_kursi', '=', 'vw_kursi_terjual.id_kursi')
                ->where('vw_kursi_terjual.id_schedule', '=', $id_schedule);
            })
            ->select('kursi.*')
            ->selectRaw('(vw_kursi_terjual.id_kursi IS NULL) AS available')
            ->where('kursi.id_studio_penayangan', '=', $schedule->id_studio_penayangan)
            ->get([
                'kursi.id_schedule',
                'kursi.id_kursi',
                'kursi.id_studio_penayangan'
            ]);
        $response = [
            'message' => 'List Seat',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);    
    }







}
