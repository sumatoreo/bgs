<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Participant;
use EventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Просмотр всех евентов
     */
    public function testEventIndex()
    {
        $this->seed(EventSeeder::class);

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonCount(5, 'payload');
    }

    /**
     * Прикрепление пользователя к евенту
     */
    public function testEventUpdate() {
        factory(Event::class)
            ->create([
                'id' => 1000
            ]);

        factory(Participant::class)
            ->create([
                'id' => 1,
                'first_name' => 'Сергей',
                'last_name' => 'Сергеев',
                'email' => 'serega@mail.ru'
            ]);

        /**
         * Добавляем пользователя в событие
         */
        $response = $this->putJson('/api/events/1000', [
            'action' => 'attach',
            'participant_id' => 1
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $this->assertDatabaseHas('event_participant', [
            'participant_id' => 1
        ]);

        /**\
         * Ошибка
         */
        $response = $this->putJson('/api/events/1000', [
            'action' => 'detached',
            'participant_id' => 1
        ]);

        $response->assertStatus(400);

        /**
         * Убираем пользователя
         */
        $response = $this->putJson('/api/events/1000', [
            'action' => 'detach',
            'participant_id' => 1
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $this->assertDatabaseMissing('event_participant', [
            'participant_id' => 1
        ]);
    }

    /**
     * Создание пользователя
     */
    public function testParticipantStore() {
        $response = $this->postJson('/api/participants', [
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'ivan@mail.ru'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonPath('payload.first_name', 'Иван');

        $response = $this->postJson('/api/participants', [
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'ivan@mail.ru'
        ]);

        $response->assertStatus(400);
    }

    /**
     * Проверка ошибок при создании пользователя
     *
     * @dataProvider updateUserFailProvider
     */
    public function testParticipantStoreFails($id, $first_name, $last_name, $email) {
        $response = $this->postJson('/api/participants', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ]);

        $response->assertStatus(400);
    }

    /**
     * Просмотр пользователя
     */
    public function testParticipantShow() {
        factory(Participant::class)
            ->create([
                'id' => 1,
                'first_name' => 'Игорь',
                'last_name' => 'Игорев',
                'email' => 'igor@mail.ru'
            ]);

        $response = $this->getJson('/api/participants/1');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ])
            ->assertJsonPath('payload.first_name', 'Игорь');
    }

    /**
     * Список пользователей
     */
    public function testParticipantIndex() {
        $this->seed(\ParticipantSeeder::class);

        $response = $this->getJson('/api/participants');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ])
            ->assertJsonCount(10, 'payload');
    }

    /**
     * Обновление пользователя
     */
    public function testParticipantUpdate() {
        factory(Participant::class)
            ->create([
                'id' => 1,
                'first_name' => 'Петя',
                'last_name' => 'Петев',
                'email' => 'petya@mail.ru'
            ]);

        $response = $this->putJson('/api/participants/1', [
            'first_name' => 'Костя',
            'last_name' => 'Костев',
            'email' => 'kostya@mail.ru'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ])
            ->assertJsonPath('payload.email', 'kostya@mail.ru');

        $this->assertDatabaseHas('participants', [
            'email' => 'kostya@mail.ru'
        ]);

        factory(Participant::class)
            ->create([
                'id' => 2,
                'first_name' => 'Петя',
                'last_name' => 'Петев',
                'email' => 'petya@mail.ru'
            ]);

        /**
         * Проверка на существование почты
         */
        $response = $this->putJson('/api/participants/2', [
            'first_name' => 'Костя',
            'last_name' => 'Костев',
            'email' => 'kostya@mail.ru'
        ]);

        $response->assertStatus(400);
    }

    /**
     * Проверка ошибок при обновлении пользователя
     *
     * @dataProvider updateUserFailProvider
     */
    public function testParticipantUpdateFails($id, $first_name, $last_name, $email) {
        factory(Participant::class)
            ->create([
                'id' => $id
            ]);

        $response = $this->putJson('/api/participants/' . $id, [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ]);

        $response->assertStatus(400);
    }

    /**
     * Удаление пользователя
     */
    public function testParticipantDestroy() {
        factory(Participant::class)
            ->create([
                'id' => 1,
                'first_name' => 'Петя',
                'last_name' => 'Петев',
                'email' => 'petya@mail.ru'
            ]);

        $response = $this->deleteJson('/api/participants/1');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $this->assertDatabaseMissing('participants', [
            'email' => 'petya@mail.ru'
        ]);
    }

    /**
     * Фильтрация пользователей
     */
    public function testParticipantIndexFilter() {
        factory(Event::class)
            ->create([
                'id' => 1000
            ]);

        factory(Participant::class)
            ->create([
                'id' => 1,
                'first_name' => 'Петя',
                'last_name' => 'Петев',
                'email' => 'petya@mail.ru'
            ]);

        factory(Participant::class)
            ->create([
                'id' => 2,
                'first_name' => 'Сергей',
                'last_name' => 'Сергеев',
                'email' => 'serega@mail.ru'
            ]);

        $response = $this->putJson('/api/events/1000', [
            'action' => 'attach',
            'participant_id' => 1
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $response = $this->getJson('/api/participants?event_id=1000');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ])
            ->assertJsonCount(1,'payload');
    }

    /**
     * Провайдер данных
     * @return array[]
     */
    public function updateUserFailProvider() {
        return  [
            [
                'id' => 1,
                'first_name' => 'Костя',
                'last_name' => 'Костев',
                'email' => 'kostya'
            ],
            [
                'id' => 2,
                'first_name' => 'Костя',
                'last_name' => '',
                'email' => 'kostya@mail.ru'
            ],
            [
                'id' => 3,
                'first_name' => '',
                'last_name' => 'Костев',
                'email' => 'kostya@mail.ru'
            ],
            [
                'id' => 4,
                'first_name' => 'Костя',
                'last_name' => 'Костев',
                'email' => ''
            ]
        ];
    }
}
