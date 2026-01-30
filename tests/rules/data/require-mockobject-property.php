<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Data;

use PHPUnit\Framework\TestCase;

final class PropertyMockTypeTest extends TestCase
{
    /** @var Foo&MockObject */
    private Foo $foo;
}

final class Foo
{
}

interface MockObject
{
}
