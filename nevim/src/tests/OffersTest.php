<?php
namespace Ondra\App\tests;
use Ondra\App\Offers\Domain\Advert;
use Ondra\App\Offers\Domain\Item;
use Ondra\App\Offers\Domain\Seller;
use Ondra\App\Offers\Infrastructure\DatabaseAdvertRepository;

class OffersTest
{
    public DatabaseAdvertRepository $offerRepository;

    /**
     * @param DatabaseAdvertRepository $offerRepository
     */
    public function __construct(DatabaseAdvertRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
    public function test() {
        $product = new Item(uniqid(), 'kytara', 2, []);
        $seller = new Seller(3);

        $this->offerRepository->save(new Advert($product, $seller, 10000, 1));
    }
}