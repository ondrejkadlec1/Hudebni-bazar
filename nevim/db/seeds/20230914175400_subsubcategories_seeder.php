<?php

use Phinx\Seed\AbstractSeed;

class SubsubcategoriesSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into public.subsubcategories (id, subcategory_id, name)
        values  (11, 12, 'Tvar 1'),
        (12, 12, 'Tvar 2'),
        (13, 13, 'Klasické kytary'),
        (14, 13, 'Španělské kytary'),
        (15, 14, 'nevim'),
        (16, 15, 'Tvar 1'),
        (17, 15, 'Tvar 2'),
        (18, 16, 'nevim'),
        (19, 17, 'nevim'),
        (20, 19, 'Kopáky'),
        (21, 19, 'Kotle'),
        (22, 19, 'Přechody'),
        (23, 19, 'Virbly'),
        (24, 20, 'Hi-hat'),
        (25, 20, 'Ride'),
        (26, 20, 'Crash'),
        (27, 20, 'China'),
        (28, 20, 'Ostatní činely');");
    }
}