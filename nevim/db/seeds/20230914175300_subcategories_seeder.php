<?php

use Phinx\Seed\AbstractSeed;

class SubcategoriesSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into public.subcategories (id, category_id, name)
        values  (19, 3, 'Bubny'),
        (20, 3, 'Činely'),
        (22, 5, 'Žesťové'),
        (23, 5, 'Dřevěné'),
        (24, 6, 'Housle'),
        (25, 6, 'Violy'),
        (26, 6, 'Violonccella'),
        (27, 6, 'Kontrabasy'),
        (28, 7, 'Banja'),
        (29, 7, 'Mandolíny'),
        (30, 9, 'Mikrofony'),
        (31, 9, 'Sluchátka'),
        (32, 10, 'Světla'),
        (33, 10, 'Efekty'),
        (34, 10, 'Reproduktory'),
        (35, 11, 'CD'),
        (36, 11, 'Vynil'),
        (37, 11, 'Kazety'),
        (14, 1, 'Elektroakustické kytary'),
        (17, 3, 'Bicí sety'),
        (16, 2, 'Akustické Baskytary'),
        (15, 2, 'Elektrické Baskytary'),
        (12, 1, 'Elektrické kytary'),
        (13, 1, 'Akustické kytary'),
        (21, 1, 'Kytarová komba');");
    }
}