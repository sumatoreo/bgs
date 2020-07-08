<?php

namespace App\Http\Controllers\Api;

use App\Events\CreateParticipantEvent;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->has('event_id')) {
            $event_id = $request->get('event_id');

            $participants = Participant::whereHas('events', function ($query) use ($event_id) {
                $query->where('event_id', $event_id);
            })->get();
        } else {
            $participants = Participant::all();
        }

        return response()
            ->json([
                'status' => 'success',
                'payload' => $participants
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('participants', 'email')]
        ], [
            'first_name.required' => 'Отсутствует обязательное поле `first_name`',
            'first_name.string' => 'Поле `first_name` должно быть типа string',
            'last_name.required' => 'Отсутствует обязательное поле `last_name`',
            'last_name.string' => 'Поле `last_name` должно быть типа string',
            'email.required' => 'Отсутствует обязательное поле `email`',
            'email.email' => 'Поле `email` должно быть email',
            'email.unique' => 'Поле `email` должно быть уникальным',
        ]);

        if ($v->fails()) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$v->errors()->all()]
                ], 400);
        }

        try {
            $participant = Participant::create($request->all());
        } catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$e->getMessage()]
                ], 500);
        }

        event(new CreateParticipantEvent('Создан пользователь ' . $request->get('email')));

        return response()
            ->json([
                'status' => 'success',
                'payload' => $participant
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Participant $participant
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Participant $participant)
    {
        return response()
            ->json([
                'status' => 'success',
                'payload' => $participant
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Participant $participant
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Participant $participant)
    {
        $v = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('participants')->ignore($participant->id)]
        ], [
            'first_name.required' => 'Отсутствует обязательное поле `first_name`',
            'first_name.string' => 'Поле `first_name` должно быть типа string',
            'last_name.required' => 'Отсутствует обязательное поле `last_name`',
            'last_name.string' => 'Поле `last_name` должно быть типа string',
            'email.required' => 'Отсутствует обязательное поле `email`',
            'email.email' => 'Поле `email` должно быть email',
            'email.unique' => 'Поле `email` уже существует',
        ]);

        if ($v->fails()) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$v->errors()->all()]
                ], 400);
        }

        try {
            $participant->update($request->all());
        } catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$e->getMessage()]
                ], 500);
        }

        return response()
            ->json([
                'status' => 'success',
                'payload' => $participant
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Participant $participant)
    {
        try {
            $participant->delete();
        } catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'stack' => [$e->getMessage()]
                ], 500);
        }

        return response()
            ->json(['status' => 'success',]);
    }
}
