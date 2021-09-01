<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Floor;
use App\Models\Facility;
use App\Models\Zone;
use App\Http\Resources\FloorResource;

class FloorController extends Controller
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
        $floors = Floor::get();
        return response()->json(FloorResource::collection($floors) );
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
            'company_id'          => 'required',
        ]);
        $user = auth()->userOrFail();
        $floor = new Floor();
        $floor->name = $request->input('name');
        $floor->company_id = $request->input('company_id');
        $floor->save();
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
        $floor = Floor::find($id);

        return response()->json( $floor );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $floor = DB::table('floors')->where('id', '=', $id)->first();
        $zoneList = Zone::select('zones.name as label', 'zones.id as value')->get();
        return response()->json( [ 'floor' => $floor, 'zones' =>  $zoneList ] );
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
        $floor = Floor::find($id);
        $floor->name     = $request->input('name');
        $floor->save();
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
        $floor = Floor::find($id);
        if($floor){
            $floor->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
