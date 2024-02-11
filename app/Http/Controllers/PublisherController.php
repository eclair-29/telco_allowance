<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Action;
use App\Models\Excess;
use App\Models\Series;
use App\Models\Status;
use App\Models\Assignee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublisherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generate()
    {
        DB::beginTransaction();
        try {
            $currentSeries = Carbon::now()->format('M') . ' ' . Carbon::now()->format('Y');
            $series = Series::where('description', $currentSeries)->first();

            if (!$series) {
                Series::create([
                    'description' => $currentSeries
                ]);

                $user = auth()->user();
                $action = Action::select('id')
                    ->where('description', 'generate worksheet')
                    ->first();

                createActionLog($user, $action, 'Generated ' . $currentSeries . ' Monthly Worksheet');

                DB::commit();

                return $currentSeries . ' worksheet generated successfully.';
            } else {
                return 'Series ' . $currentSeries . ' already exists.';
            }
        } catch (Throwable $th) {
            DB::rollBack();
            return 'Error on generating worksheet. Please contact ISD for support. ' . $th->getMessage();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $series = Series::all();

        return view('publisher.index', [
            'series' => $series
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
