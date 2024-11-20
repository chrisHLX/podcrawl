<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WowClass; // Make sure to import your model

class WowClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            ['class' => 'Death Knight', 'spec' => 'Blood'],
            ['class' => 'Death Knight', 'spec' => 'Frost'],
            ['class' => 'Death Knight', 'spec' => 'Unholy'],
            ['class' => 'Demon Hunter', 'spec' => 'Havoc'],
            ['class' => 'Demon Hunter', 'spec' => 'Vengeance'],
            ['class' => 'Druid', 'spec' => 'Balance'],
            ['class' => 'Druid', 'spec' => 'Feral'],
            ['class' => 'Druid', 'spec' => 'Guardian'],
            ['class' => 'Druid', 'spec' => 'Restoration'],
            ['class' => 'Hunter', 'spec' => 'Beast Mastery'],
            ['class' => 'Hunter', 'spec' => 'Marksmanship'],
            ['class' => 'Hunter', 'spec' => 'Survival'],
            ['class' => 'Mage', 'spec' => 'Arcane'],
            ['class' => 'Mage', 'spec' => 'Fire'],
            ['class' => 'Mage', 'spec' => 'Frost'],
            ['class' => 'Monk', 'spec' => 'Brewmaster'],
            ['class' => 'Monk', 'spec' => 'Mistweaver'],
            ['class' => 'Monk', 'spec' => 'Windwalker'],
            ['class' => 'Paladin', 'spec' => 'Holy'],
            ['class' => 'Paladin', 'spec' => 'Protection'],
            ['class' => 'Paladin', 'spec' => 'Retribution'],
            ['class' => 'Priest', 'spec' => 'Discipline'],
            ['class' => 'Priest', 'spec' => 'Holy'],
            ['class' => 'Priest', 'spec' => 'Shadow'],
            ['class' => 'Rogue', 'spec' => 'Assassination'],
            ['class' => 'Rogue', 'spec' => 'Outlaw'],
            ['class' => 'Rogue', 'spec' => 'Subtlety'],
            ['class' => 'Shaman', 'spec' => 'Elemental'],
            ['class' => 'Shaman', 'spec' => 'Enhancement'],
            ['class' => 'Shaman', 'spec' => 'Restoration'],
            ['class' => 'Warlock', 'spec' => 'Affliction'],
            ['class' => 'Warlock', 'spec' => 'Demonology'],
            ['class' => 'Warlock', 'spec' => 'Destruction'],
            ['class' => 'Warrior', 'spec' => 'Arms'],
            ['class' => 'Warrior', 'spec' => 'Fury'],
            ['class' => 'Warrior', 'spec' => 'Protection'],
        ];

        foreach ($classes as $class) {
            WowClass::create($class); // Create a new WowClass entry
        }
    }
}
