<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::create(['name' => 'ошибка', 'description' => 'Какая-то ошибка в коде или проблема с функциональностью']);
        Label::create(['name' => 'документация', 'description' => 'Задача которая касается документации']);
        Label::create(['name' => 'дубликат', 'description' => 'Повтор другой задачи']);
        Label::create(['name' => 'доработка', 'description' => 'Новая фича, которую нужно запилить']);
    }
}
