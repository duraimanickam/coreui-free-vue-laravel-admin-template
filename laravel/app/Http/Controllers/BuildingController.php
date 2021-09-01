<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Building;
use App\Models\Facility;
use App\Models\Zone;
use App\Http\Resources\BuildingResource;

class BuildingController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buildings = Building::get();
        return response()->json(BuildingResource::collection($buildings) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'zones' => Zone::select('zones.name as label', 'zones.id as value')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:64',
            'facility_id'          => 'required',
        ]);
        $user = auth()->userOrFail();
        $building = new Building();
        $building->name = $request->input('name');
        $building->facility_id = $request->input('facility_id');
        $building->save();
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $building = Building::find($id);

        return response()->json( $building );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $building = DB::table('buildings')->where('id', '=', $id)->first();
        $zoneList = Zone::select('zones.name as label', 'zones.id as value')->get();
        return response()->json( [ 'building' => $building, 'zones' =>  $zoneList ] );
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
        //var_dump('bazinga');
        //die();
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:64'
        ]);
        $building = Building::find($id);
        $building->name     = $request->input('name');
        $building->save();
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $building = Building::find($id);
        if($building){
            $building->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
