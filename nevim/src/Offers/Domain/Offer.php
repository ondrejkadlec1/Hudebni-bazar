<?php
namespace Ondra\App\System\Domain;

use Nette\Security\SimpleIdentity;
class Offer
{
    private Product $product;
    private SimpleIdentity $seller;
    private Quantity $quantity;

    /**
     * @param Product $product
     * @param SimpleIdentity $seller
     */
    public function __construct(Product $product, SimpleIdentity $seller)
    {
        $this->product = $product;
        $this->seller = $seller;
    }

}