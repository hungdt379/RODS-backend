<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //TODO: can change Role permission to using laravel packages
        $this->call(UsersSeeder::class);
        $this->call(Category::class);
        $this->call(DishInCombo::class);
        $this->call(Feedback::class);
        $this->call(Menu::class);
        $this->call(Order::class);
        $this->call(QueueOrder::class);
    }
}
