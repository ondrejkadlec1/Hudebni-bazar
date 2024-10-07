<?php

use Phinx\Seed\AbstractSeed;

class CategoriesSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into public.categories (id, name)
        values  (1, 'Kytary'),
        (2, 'Baskytary'),
        (3, 'Bicí'),
        (4, 'Klávesy'),
        (5, 'Dechové nástroje'),
        (6, 'Smyčcové nástroje'),
        (7, 'Ostatní strunné nástroje'),
        (8, 'DJ'),
        (9, 'Zvuk'),
        (10, 'Pódiová technika'),
        (11, 'Hodební nosiče'),
        (12, 'Ostatní');");
    }
}