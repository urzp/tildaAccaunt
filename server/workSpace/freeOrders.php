<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function index(Request $request, string $service_domain)
    {
        $hours   = (int) $request->hours ?? 2;
        $service = (int) $request->service;
        $keys_arr = ['c7f7a5d326c23571379604c2bede722e', 'S3XXVdQs7O7r8yGwEUWRhz00vC2FwgIi9TeGdf43FbaphrwHPTo0889AW4OQ', '6049c470a3d3bd7ecd9f87dbc26687c0',  'aef93a6ec9e3954674d340535175c135', 'bca7e2f268a294e4ea07b05088fb8437', '70452327e01b18bfeb525f9053899cc4', '0bc4f5a20439381a45ba7c579529e0a0'];
        if(!in_array($request->key, $keys_arr)){
            return response()->json(['success' => 'false']);
        };
        if (!$request->link) {
            return response()->json(['success' => 'success']);
        };

        $link = Link::query()
            ->where([
                ["link", $request->link],
                ["service_id", $service],
            ])
            ->first();
        
        if (!$link) {
            Link::query()->create([
                "link"       => $request->link,
                "last_time"  => now(),
                "service_id" => $service,
            ]);
            $this->sendApiAddOrder($request, $service_domain);
        } else {
            $last_time = Carbon::parse($link->last_time);
            if (now() > $last_time->addHours($hours)) {
                $link->update([
                    "last_time" => now(),
                ]);
                $this->sendApiAddOrder($request, $service_domain);
            }
        }

        return response()->json(['success' => 'success']);
    }

    private function sendApiAddOrder(Request $request, string $service_domain)
    {
        $url_api = $request->url_api ?? "/api/v2";
        Http::post("https://$service_domain" . $url_api, [
            "key"      => $request->key,
            "action"   => "add",
            "service"  => $request->service,
            "link"     => $request->link,
            "quantity" => $request->quantity,
        ]);
    }
}
