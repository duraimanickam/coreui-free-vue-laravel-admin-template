<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Facility;
use App\Models\Zone;
use App\Http\Resources\FacilityResource;

class FacilityController extends Controller
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
        $facilities = Facility::get();
        return response()->json(FacilityResource::collection($facilities) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'zonelist' => Zone::select('zones.name as label', 'zones.id as value')->get(),
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
            'zone_id'          => 'required',
        ]);
        $user = auth()->userOrFail();
        $facility = new Facility();
        $facility->name = $request->input('name');
        $facility->zone_id = $request->input('zone_id');
        $facility->save();
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
        $zone = Facility::find($id);

        return response()->json( $zone );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $facility = DB::table('facilities')->where('id', '=', $id)->first();
        $zoneList = Zone::select('zones.name as label', 'zones.id as value')->get();
        return response()->json( [ 'facility' => $facility, 'zonelist' =>  $zoneList ] );
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
        $zone = Facility::find($id);
        $zone->name     = $request->input('name');
        $zone->save();
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
        $zone = Facility::find($id);
        if($zone){
            $zone->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
