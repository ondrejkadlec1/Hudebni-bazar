<?php

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    public function run() : void
    {
        $this->execute("insert into users (username, email, created_at, id, password, is_seller, authtoken)
        values  ('ondra', 'email@email.com', '2024-06-27 16:54:59.000000', '6686943fc48c8', '$2y$10$dKIe32k.pWn3SG8slMjQCOzKmsxgVVNwZNnbHqQAiyvKuikAhPKb2', true, '1t8d4er12tzui'),
        ('Tony', 'anthony@gmail.com', '2024-07-04 12:16:35.000000', '668682c59c0f4', '$2y$10$i3jwZ5WiIQyirlT6iJISQ.5kg5yNODFAh16QQ9AnL7fFXouMWtGQW', true, 'erdujekqo85e1'),
        ('PanTestovač', 'mr.docker@seznam.cz', '2024-07-04 19:43:19.000000', '668682c5b1185', '$2y$10$VrLpnw6xehk5Mmuw0ba5oeh7i8pldXZ5E7gAs9OA0NPRj9H1QKY.m', true, '56987sdrtfewt');");
    }
}