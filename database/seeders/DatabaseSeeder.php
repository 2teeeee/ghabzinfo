<?php

namespace Database\Seeders;

use App\Models\Center;
use App\Models\City;
use App\Models\Organ;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = User::factory()->create([
            'name' => 'ادمین',
            'username' => 'admin',
            'mobile' => '09173326706',
            'password' => Hash::make('123456')
        ]);

        Role::create(
            [
                'name' => 'admin',
                'label' => 'ادمین'
            ],
            [
                'name' => 'city',
                'label' => 'مسئول شهرستان'
            ],
            [
                'name' => 'organ',
                'label' => 'مسئول سازمان'
            ],
            [
                'name' => 'unit',
                'label' => 'مسئول واحد'
            ],
            [
                'name' => 'center',
                'label' => 'مسئول مرکز'
            ],
            [
                'name' => 'user',
                'label' => 'کاربر معمولی'
            ],
        );

        $user->assignRole('admin');

        $city = City::create([
            'name' => 'شیراز'
        ]);

        $organ = Organ::create([
            'city_id' => $city->id,
            'name' => 'دانشگاه علوم پزشکی شیراز'
        ]);

        $unit = Unit::create([
            'organ_id' => $organ->id,
            'name' => 'بیمارستان ها'
        ]);

        $center = Center::create([
            'unit_id' => $unit->id,
            'name' => 'بیمارستان شهید چمران'
        ]);
    }
}
