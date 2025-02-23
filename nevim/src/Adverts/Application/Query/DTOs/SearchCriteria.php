<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\DTOs;

use Ondra\App\Shared\Application\Exceptions\InvalidValueException;

final class SearchCriteria
{
	public static string $asc = "asc";
	public static string $desc = "desc";
	public static string $orderByPrice = "price";
	public static string $orderByDate = "date";
    public static array $allowed = [
        'limit' => 'integer', 'offset' => 'integer', 'orderBy' => 'string', 'direction' => 'string',
        'categoryId' => 'integer', 'sellerId' => 'string',
        'maxPrice' => 'integer', 'minPrice' => 'integer', 'stateIds' => 'array', 'brand' => 'string',
        'expression' => 'string'
    ];
    public static array $filters = [
        'orderBy' => 'string', 'direction' => 'string',
        'maxPrice' => 'integer', 'minPrice' => 'integer', 'stateIds' => 'array', 'brand' => 'string',
    ];
    public function __construct(
        public ?int $limit,
        public int $offset,
        public string $orderBy,
        public string $direction,
        public ?int $categoryId = null,
        public ?string $sellerId = null,
        public ?int $maxPrice = null,
        public ?int $minPrice = null,
        public ?array $stateIds = null,
        public ?string $brand = null,
        public ?string $expression = null,
    ){
    }

    public static function fromArray(array $array): SearchCriteria
    {
        self::validate($array);
        $result = new SearchCriteria(
            limit: null,
            offset: 0,
            orderBy: self::$orderByDate,
            direction: self::$desc,
        );
        foreach ($array as $name => $value){
            $result->$name = $value;
        }
        return $result;
    }

    public static function validate(array $array): void
    {
        $diff = array_diff(array_keys($array), array_keys(self::$allowed));
        if ($diff !== []){
            throw new InvalidValueException('Unallowed attribute(s): ' . implode(", ", $diff));
        }
        foreach ($array as $name => $value){
            if (getType($value) !== self::$allowed[$name]){
                throw new InvalidValueException('Type of ' . $name . ' should be ' . self::$allowed[$name] . ', but is ' . getType($value) . '.');
            }
        }
    }

    public function addArray(array $array): void
    {
        self::validate($array);
        foreach ($array as $name => $value){
            $this->$name = $value;
        }
    }
}
