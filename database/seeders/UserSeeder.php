<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private array $usersNames = [
        'Денисова Инна Евгеньевна',
        'Кузнецова Инга Андреевна',
        'Казаков Савва Романович',
        'Соловьёва Клара Владимировна',
        'Игнатова Анфиса Дмитриевна',
        'Яковлеваа Раиса Сергеевна',
        'Титова Полина Евгеньевна',
        'Захарова Марина Андреевна',
        'Лаврентьева Екатерина Евгеньевна',
        'Тарасова Ника Борисовна',
        'Зайцева Ульяна Владимировна',
        'Зимина Екатерина Львовна',
        'Владимиров Вячеслав Иванович',
        'Горшкова Юлия Ивановна',
        'Абрамова Лидия Владимировна',
        'Иванов Артур Игнатьевич',
    ];

    public function run(): void
    {
        foreach ($this->usersNames as $name) {
            User::factory(['name' => $name])->create();
        }
    }
}
