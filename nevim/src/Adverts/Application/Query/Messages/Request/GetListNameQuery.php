<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Messages\Request;

use Ondra\App\Shared\Application\Query\Query;

final class GetListNameQuery implements Query
{
	public static string $isCategory = "category";
	public static string $isSubcategory = "subcategory";
	public static string $isSubsubcategory = "subsubcategory";
	public static string $isUser = "user";
	public function __construct(public readonly int $id, public readonly string $type)
	{
	}
}
