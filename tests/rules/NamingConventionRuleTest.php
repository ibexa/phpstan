<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\NamingConventionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\NamingConventionRule>
 */
final class NamingConventionRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
       return new NamingConventionRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/NamingConvention/WrongName.php',
                __DIR__ . '/Fixtures/NamingConvention/SimpleThing.php',
                __DIR__ . '/Fixtures/NamingConvention/SimpleClass.php',
                __DIR__ . '/Fixtures/NamingConvention/CorrectNameInterface.php',
                __DIR__ . '/Fixtures/NamingConvention/CorrectNameTrait.php',
                __DIR__ . '/Fixtures/NamingConvention/AbstractCorrectClass.php',
            ],
            [
                [
                    'Interface "WrongName" should have "Interface" suffix',
                    11,
                ],
                [
                    'Trait "SimpleThing" should have "Trait" suffix',
                    11,
                ],
                [
                    'Abstract class "SimpleClass" should have "Abstract" prefix',
                    11,
                ],
            ]
        );
    }
}
