<?php

namespace Database\Seeders;

use \App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private array $usersNames = [
        'Инна Евгеньевна Денисова',
        'Инга Андреевна Кузнецоваа',
        'Казакова Савва Романович',
        'Соловьёва Клара Владимировна',
        'Анфиса Дмитриевна Игнатоваа',
        'Яковлеваа Раиса Сергеевна',
        'Полина Евгеньевна Титоваа',
        'Марина Андреевна Захароваа',
        'Екатерина Евгеньевна Лаврентьева',
        'Ника Борисовна Тарасова',
        'Зайцеваа Ульяна Владимировна',
        'Зиминаа Екатерина Львовна',
        'Владимирова Вячеслав Иванович',
        'Юлия Ивановна Горшковаа',
        'Абрамоваа Лидия Владимировна',
        'Артур',
    ];

    public function run(): void
    {
        foreach ($this->usersNames as $name) {
            User::factory(['name' => $name])->create();
        }
    }
}
