<?php

use Phinx\Seed\AbstractSeed;

class UsersInfoSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into public.users_info (description, id)
        values  ('Nevim.', '6686943fc48c8'),
        ('Já bych věděl, ale…', '668682c5b1185'),
        ('Taky nevim.', '668682c59c0f4');");
    }
}