<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ForeignKeys extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("
        ALTER TABLE users ADD CONSTRAINT user_unique UNIQUE (id);

        ALTER TABLE sellers DROP CONSTRAINT fk_id,
            ADD CONSTRAINT seller_unique UNIQUE (id),
            ADD CONSTRAINT fk_id 
                FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE;

        ALTER TABLE adverts DROP CONSTRAINT fk_product,
            DROP CONSTRAINT fk_seller_id,
            ADD CONSTRAINT fk_seller_id 
                FOREIGN KEY (seller_id) REFERENCES sellers (id) ON DELETE CASCADE;

        ALTER TABLE superordinate_category DROP CONSTRAINT fk_higher,
            DROP CONSTRAINT fk_lower,
            ADD CONSTRAINT fk_lower
                FOREIGN KEY (lower_id) REFERENCES categories (id) ON DELETE CASCADE,
            ADD CONSTRAINT fk_higher
                FOREIGN KEY (higher_id) REFERENCES categories (id) ON DELETE CASCADE;
            
        ALTER TABLE items DROP CONSTRAINT lowest_category_fk,
            ALTER COLUMN lowest_category_id SET DEFAULT 43,
            ALTER COLUMN state_id SET DEFAULT 3,
            DROP CONSTRAINT fk_state,
            ADD CONSTRAINT fk_lowest_category 
                FOREIGN KEY (lowest_category_id) REFERENCES categories (id) ON DELETE SET DEFAULT,
            ADD CONSTRAINT fk_advert 
                FOREIGN KEY (id) REFERENCES adverts (id) ON DELETE CASCADE,
            ADD CONSTRAINT fk_state 
                FOREIGN KEY (state_id) REFERENCES states (id) ON DELETE SET DEFAULT; 

        ALTER TABLE item_images DROP CONSTRAINT fk_item_id,
            ADD CONSTRAINT fk_item_id FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE;
");
	}
}