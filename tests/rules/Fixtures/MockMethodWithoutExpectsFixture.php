<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class MockMethodWithoutExpectsFixture extends TestCase
{
    private \Countable & MockObject $property;

    public function testBareMethodOnMock(): void
    {
        $mock = $this->createMock(\Countable::class);
        $mock->method('count')->willReturn(1);
    }

    public function testBareMethodOnMockProperty(): void
    {
        $this->property->method('count')->willReturn(1);
    }

    public function testMethodWithExpects(): void
    {
        $mock = $this->createMock(\Countable::class);
        $mock->expects($this->once())->method('count')->willReturn(1);
    }

    public function testMethodOnStub(): void
    {
        $stub = self::createStub(\Countable::class);
        $stub->method('count')->willReturn(1);
    }
}
