<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $events = Event::with('participants')
            ->get();

        return response()
            ->json([
                'status' => 'success',
                'payload' => $events
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Event $event)
    {
        $v = Validator::make($request->all(), [
            'action' => [
                'required',
                Rule::in(['attach', 'detach'])
            ],
            'participant_id' => ['required', 'integer', 'exists:participants,id']
        ], [
            'action.required' => 'Отсутствует обязательное поле `action`',
            'action.in' => 'Поле `action` должно содержать `attach` или `detach`',
            'participant_id.required' => 'Отсутствует обязательное поле `participant_id`',
            'participant_id.integer' => 'Поле `participant_id` должно быть типа int',
            'participant_id.exists' => 'Пользователь не найден',
        ]);

        if ($v->fails()) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$v->errors()->all()]
                ], 400);
        }

        $participant_id = $request->input('participant_id');
        $participant = Participant::find($participant_id);
        $action = $request->input('action');

        try {
            $event->participants()
                ->$action($participant);
        } catch (QueryException $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => ['Пользователь уже был добавлен на другое мероприятие']
                ], 400);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$e->getMessage()]
                ], 500);
        }

        return response()
            ->json(['status' => 'success']);
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
