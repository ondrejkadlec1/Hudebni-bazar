<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;
use Rector\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector;
use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;
use Rector\CodeQuality\Rector\FuncCall\BoolvalToTypeCastRector;
use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector;
use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\CodingStyle\Rector\FuncCall\StrictArraySearchRector;
use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;
use Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([
		__DIR__ . '/src',
	]);

	$rectorConfig->sets([
		LevelSetList::UP_TO_PHP_81,
	]);

	$rectorConfig->importNames();
	$rectorConfig->importShortClasses(true);

	$rectorConfig->rule(FinalizeClassesWithoutChildrenRector::class);
	$rectorConfig->rule(PrivatizeFinalClassMethodRector::class);
	$rectorConfig->rule(PrivatizeFinalClassPropertyRector::class);
	$rectorConfig->rule(ArrayKeyExistsTernaryThenValueToCoalescingRector::class);
	$rectorConfig->rule(BooleanNotIdenticalToNotIdenticalRector::class);
	$rectorConfig->rule(BoolvalToTypeCastRector::class);
	$rectorConfig->rule(CallableThisArrayToAnonymousFunctionRector::class);
	$rectorConfig->rule(ChangeArrayPushToArrayAssignRector::class);
	$rectorConfig->rule(CleanupUnneededNullsafeOperatorRector::class);
	$rectorConfig->rule(CombinedAssignRector::class);
	$rectorConfig->rule(CommonNotEqualRector::class);
	$rectorConfig->rule(CompleteMissingIfElseBracketRector::class);
	$rectorConfig->rule(ConvertStaticPrivateConstantToSelfRector::class);
	$rectorConfig->rule(ExplicitBoolCompareRector::class);
	$rectorConfig->rule(ForRepeatedCountToOwnVariableRector::class);
	$rectorConfig->rule(InlineArrayReturnAssignRector::class);
	$rectorConfig->rule(SimplifyBoolIdenticalTrueRector::class);
	$rectorConfig->rule(SimplifyEmptyArrayCheckRector::class);
	$rectorConfig->rule(SimplifyUselessVariableRector::class);
	$rectorConfig->rule(SwitchNegatedTernaryRector::class);
	$rectorConfig->rule(UnusedForeachValueToArrayKeysRector::class);
	$rectorConfig->rule(UnwrapSprintfOneArgumentRector::class);
	$rectorConfig->rule(ConsistentImplodeRector::class);
	$rectorConfig->rule(CountArrayToEmptyArrayComparisonRector::class);
	$rectorConfig->rule(MakeInheritedMethodVisibilitySameAsParentRector::class);
	$rectorConfig->rule(NullableCompareToNullRector::class);
	$rectorConfig->rule(RemoveFinalFromConstRector::class);
	$rectorConfig->rule(SplitDoubleAssignRector::class);
	$rectorConfig->rule(StaticArrowFunctionRector::class);
	$rectorConfig->rule(StaticClosureRector::class);
	$rectorConfig->rule(StrictArraySearchRector::class);
	$rectorConfig->rule(RecastingRemovalRector::class);
	$rectorConfig->rule(RemoveAlwaysTrueIfConditionRector::class);
	$rectorConfig->rule(RemoveAndTrueRector::class);
	$rectorConfig->rule(RemoveUnusedForeachKeyRector::class);
	$rectorConfig->rule(RemoveUnusedPrivateClassConstantRector::class);
	$rectorConfig->rule(RemoveUnusedPrivateMethodParameterRector::class);
	$rectorConfig->rule(RemoveUnusedPrivateMethodRector::class);
	$rectorConfig->rule(RemoveUselessParamTagRector::class);
	$rectorConfig->rule(RemoveUselessReturnTagRector::class);
	$rectorConfig->rule(RemoveUselessVarTagRector::class);

	$rectorConfig->skip([
		StaticCallToFuncCallRector::class,
		AddLiteralSeparatorToNumberRector::class,
	]);
};
