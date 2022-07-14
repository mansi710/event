<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class CalanderEventController extends Controller
{
    public function index()
    {
        return view('event.calander');
    }

    public function fetchEvents(Request $request)
    {

        $data = Event::select('*')->whereDate('start', '>=', $request->start)
        ->whereDate('end', '<=', $request->end)->get();
        return response()->json($data);
    }
    /**

     * Write code on Method

     *

     * @return response()

     */

    public function editEvent($id)
    {
        $event = Event::find($id);
   
        if($event){
            return response()->json([
                'status'=>200,
                'editEvent'=> $event,
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=> 'Event not found',
            ]);
        }
    }
    public function updateEvent(Request $request, $id)
    {
        $event = Event::find($id)->update([
            'title'=>$request->title,
            'start' => $request->start,
            'end' => $request->end,
        ]);
        return response()->json($event);
    }
    public function destroyEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'error' => 'Unable To Locate the Event'
            ]);
        }
        $event->delete();

        return $id;
    }
}
