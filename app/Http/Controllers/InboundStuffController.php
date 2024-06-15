<?php

namespace App\Http\Controllers;

use App\Helpers\Apiformatter;
use Illuminate\Http\Request;
use App\Models\InboundStuff;
use App\Models\StuffStock;
use App\Models\stuff;
use Illuminate\Support\str;

class InboundStuffController extends Controller
{
    public function store(Request $request)
    {
        try {
            $this->validate($request,[
                'stuff_id' => 'required',
                'total'=>'required',
                'date'=>'required',
                'proff_file'=>'required|image',
            ]);

            $nameImage = str::random(5) . $request->file('proff_file')->getClientOriginalName();
            $request->file('proff_file')->move('uplode-images',$nameImage);
            $pathImage = url('uplode-images' . $nameImage);

            $inboundData = InboundStuff::create([
                'stuff_id' => $request->stuff_id,
                'total'=> $request->total,
                'date'=> $request->date,
                'proff_file'=> $pathImage
            ]);

            if ($inboundData) {
                $stockData = StuffStock::where('stuff_id',$request->stuff_id)->first();
                if ($stockData) {
                    $total_available = (int)$stockData['total_available'] + (int) $request->total;
                    $stockData->update(['total_available'=> $total_available]);
                }else {
                    stuffstock::create([
                        'stuff_id'=> $request->sruff_id,
                        'total_available'=> $request->total,
                        'total_defect'=>0
                    ]);
                }

                $stuffwithinboundAndstock = Stuff::where('id',$request-stuff_id)->with('inboundstuffs', 'stuffstock')->first();
                return Apiformatter::sendResponse(200, 'success', $stuffwithinboundAndstock);
            }
        } catch (\Exception $err) {
            return Apiformatter::sendResponse(400, 'bad request', $err->getmessage());
        }
    }

    public function destroy()
    {
        try {
            $inboundData = IboundStuff::where('id', $id)->first();
            $stuffid = $inboundData['stuff_id'];
            $totalinbound = $inboundData['total'];
            $inboundData->delete();

            $total_available = (int)$inboundData['total_available'] - (int)$inboundData['total'];
            
            $minusTotalStock = stuffstock::where('stuff_id', $inboundData['stuff_id'])->update(['total_available'=>$total_available]);

            if ($minusTotalStock) {
                $updatedstuffwithInboundAndStock = stuff::where('id',$inboundData['stuff_id'])->with('inboundStuffs','stuffstock')->first();

                $inboundData->delete();
                return Apiformatter::sendResponse(200, 'success', $updatedstuffwithInboundAndStock);
            }
        } catch (\Exception $err) {
            return Apiformatter::sendResponse(400, 'bad request', $err->getmessage());
        }
    }
}
