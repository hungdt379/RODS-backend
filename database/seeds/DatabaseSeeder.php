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
        $this->call(Category::class);
        $this->call(Menu::class);
        $this->call(DishInCombo::class);
        $this->call(SearchCombo129::class);
        $this->call(SearchCombo169::class);
        $this->call(SearchCombo209::class);
    }
}
