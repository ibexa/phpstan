<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures;

final class RequireClosureReturnTypeFixture
{
    public function closureWithoutReturnType(): void
    {
        // Error: Closure without return type
        $closure = static function ($x) {
            return $x * 2;
        };
    }

    public function closureWithReturnType(): void
    {
        // OK: Closure has return type
        $closure = static function (int $x): int {
            return $x * 2;
        };
    }

    public function arrowFunctionWithoutReturnType(): void
    {
        // Error: Arrow function without return type
        $arrow = static fn ($x) => $x * 2;
    }

    public function arrowFunctionWithReturnType(): void
    {
        // OK: Arrow function has return type
        $arrow = static fn (int $x): int => $x * 2;
    }

    public function closureWithVoidReturnType(): void
    {
        // OK: Closure has void return type
        $closure = static function (): void {
            echo 'Hello';
        };
    }

    public function arrowFunctionWithMixedReturnType(): void
    {
        // OK: Arrow function has mixed return type
        $arrow = static fn ($x): mixed => $x;
    }

    public function nestedClosuresWithoutReturnType(): void
    {
        // Error: Outer closure without return type
        $outer = static function () {
            // Error: Inner closure without return type
            return static function ($x) {
                return $x * 2;
            };
        };
    }

    public function arrayMapWithoutReturnType(): void
    {
        // Error: Closure without return type
        $result = array_map(static function ($x) {
            return $x * 2;
        }, [1, 2, 3]);
    }
}
