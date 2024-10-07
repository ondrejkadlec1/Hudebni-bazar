<?php

use Phinx\Seed\AbstractSeed;

class ItemsSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into public.items (name, state_id, details, id, subsubcategory_id, brand)
        values  ('Sextant', 1, '', '6695264cc1897', 16, null),
        ('Agama', 2, 'Je to hezké mít ji doma. Pořád jenom spí a u toho dělá velký rámus. Dopoučila bych ji dospívajícím dětem protože je to skvělý hudební nástroj a hodně se na ní naučí.', '66aa21c96bf8a', 25, 'Cort'),
        ('Vydra', 6, 'Nefunkční vydra', '66aa7482b7102', 12, 'vydrex'),
        ('Elektrický kytara', 1, '', '6685a4cbd5765', 11, null),
        ('Gregor', 1, '', '6685449a20c7a', 21, null),
        ('Kabel', 3, 'Tukabel', '668bf8218730c', 18, null),
        ('Doma vyrobené bicí', 3, '4x víko od popelnice, 3x kastrol, 1x na podlahu', '6685bcb23df07', 20, null),
        ('Trinitro todleto', 2, '', '6686dfeb93de8', 13, null),
        ('Plyn', 1, '', '668bf5b110241', 19, null),
        ('Raketomet', 1, 'Vhodný k ničení tanků, vozidel a nízko letících objektů, jako jsou vrtulníky.', '6685c30006038', 12, null),
        ('Plyn', 1, '', '668bf5532fad6', 20, null),
        ('Játra', 4, 'Jedná se o věkem poškozená játra, na kterých se podepsala pravidelná konzumece alkoholu. Hodí se spíše pro hobby použití. Jsou dostatečně tvrdá i pro metal core nebo hardcore. Prodávám je, protože mi už nestačí a tak kupuji větší.', '668423027b42f', 17, null),
        ('Igor', 1, '', '66853d167b1f5', 14, null),
        ('Bicí', 1, 'Sou úplně na ho*no!', '66841d6a8292b', 15, null),
        ('Plyn', 1, 'Jedovatý plyn.', '668bf0315ee8c', 13, null),
        ('Raketa Agatha A1', 6, 'Dolet - 950 m svisle. Nelze použít do raketometu.', '668682c58243e', 12, null),
        ('Jekor', 1, '', '66854cf4c6793', 11, null),
        ('Plyn', 1, '', '668bf4687a2d1', 13, null),
        ('Tukabel', 5, '', '668bfa5fba223', 16, null),
        ('Plyn', 1, 'Jedovatý plyn.', '668bf06bb3628', 14, null),
        ('Baskytara Fender', 2, 'Počet strun: 4, počet pražců: 26, počet krků: 1.', '6685c20bd53af', 12, null),
        ('kytara', 2, '', '668408a0d7c13', 22, null),
        ('Játra', 4, 'Jedná se o věkem poškozená játra, na kterých se podepsala pravidelná konzumece alkoholu. Hodí se spíše pro hobby použití. Jsou dostatečně tvrdá i pro metal core nebo hardcore. Prodávám je, protože mi už nestačí a tak kupuji větší.', '6685c11a63c06', 11, null),
        ('Plyn', 1, 'Jedovatý plyn.', '668befb16a39f', 13, null),
        ('Bicí', 3, '', '6686943f9ea0c', 14, null);");
    }
}