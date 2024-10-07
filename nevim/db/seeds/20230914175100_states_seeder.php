<?php

use Phinx\Seed\AbstractSeed;

class StatesSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into public.states (name, id)
        values  ('Nový', 1),
        ('Opotřebený', 4),
        ('Poškozený', 5),
        ('Nefunkční', 6),
        ('Používaný', 3),
        ('Zánovní', 2);");
    }
}