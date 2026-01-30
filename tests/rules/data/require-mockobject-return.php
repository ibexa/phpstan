<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Data;

use PHPUnit\Framework\TestCase;

final class ReturnMockTypeTest extends TestCase
{
    private function createFoo(): Foo
    {
        $foo = $this->createMock(Foo::class);

        return $foo;
    }

    private function createFooOk(): Foo&MockObject
    {
        return $this->createMock(Foo::class);
    }
}

final class Foo
{
}

interface MockObject
{
}
