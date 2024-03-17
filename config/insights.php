<?php
// config/insights.php

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\NoSilencedErrorsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\GlobalKeywordSniff;
use SlevomatCodingStandard\Sniffs\Classes\DisallowLateStaticBindingForConstantsSniff;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;

return [
    'remove' => [
        ForbiddenPublicPropertySniff::class,
        ForbiddenGlobals::class,
        DisallowLateStaticBindingForConstantsSniff::class,
        AssignmentInConditionSniff::class,
        DisallowEmptySniff::class,
        NoSilencedErrorsSniff::class,

        SuperfluousInterfaceNamingSniff::class,
        SuperfluousExceptionNamingSniff::class,
        ForbiddenNormalClasses::class,
        ForbiddenTraits::class,
        FunctionLengthSniff::class
    ],
];
