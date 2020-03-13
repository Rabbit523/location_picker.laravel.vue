<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Zone;

class ZonesController extends Controller
{
    public function getAllZones(Request $request) {
        $zones = Zone::where('provider_id', $request->id)->get();
        return response()->json(["zones" => $zones]);
    }

    public function getZone(Request $request) {
        $zone = Zone::where('id', $request->id)->first();
        return response()->json(['zone' => $zone]);
    }

    public function newZone(Request $request) {
        $zone = Zone::create($request->only(['provider_id', 'name', 'rate', 'time', 'path']));
        $zones = Zone::where('provider_id', $request->provider_id)->get();
        return response()->json(["zones" => $zones]);
    }

    public function updateZone(Request $request) {
        $zone = Zone::where('id', $request->id)->first();
        $zone->name = $request->name;
        $zone->rate = $request->rate;
        $zone->time = $request->time;
        $zone->path = $request->path;
        $zone->save();
        $zones = Zone::where('provider_id', $request->provider_id)->get();
        return response()->json(["zones" => $zones]);
    }

    public function deleteZone(Request $request) {
        $zone = Zone::where('id', $request->id)->first();
        $provider_id = $zone->provider_id;
        $zone->delete();
        $zones = Zone::where('provider_id', $provider_id)->get();
        return response()->json(["zones" => $zones]);
    }

    public function polygonValidate(Request $request) {
        $location = $request->location;
        $zones = Zone::where('provider_id', $request->provider_id)->get();
        $response = [];
        foreach ($zones as $zone) {
            $path = json_decode($zone->path);
            $vertices_x = []; $vertices_y = [];
            foreach ($path as $sel) {
                array_push($vertices_x, $sel->lat);
                array_push($vertices_y, $sel->lng);
            }
            $points_polygon = count($vertices_x) - 1;
            if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $location['lat'], $location['lng'])){
               array_push($response, "(".$location['lat'].", ".$location['lng'].") is inside of ".$zone->name);
            }
        }
        if (count($response) < 1) {
            array_push($response, "(".$location['lat'].", ".$location['lng'].") isn't inside of any zones");
        }
        return response()->json(['result' => $response]);
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
            ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
            $c = !$c;
        }
        return $c;
    }
}
