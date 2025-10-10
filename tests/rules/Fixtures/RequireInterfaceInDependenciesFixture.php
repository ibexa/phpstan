<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures;

use Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\ClassWithoutInterface;
use Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\ConcreteClass;
use Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\TestInterface;

final class RequireInterfaceInDependenciesFixture
{
    private ConcreteClass $concreteClass;

    private TestInterface $testInterface;

    private ClassWithoutInterface $classWithoutInterface;

    public function __construct(
        ConcreteClass $concreteClass,
        TestInterface $testInterface,
        ClassWithoutInterface $classWithoutInterface
    ) {
        $this->concreteClass = $concreteClass;
        $this->testInterface = $testInterface;
        $this->classWithoutInterface = $classWithoutInterface;
    }

    public function methodWithConcreteClass(ConcreteClass $class): void
    {
    }

    public function methodWithInterface(TestInterface $interface): void
    {
    }

    public function methodWithoutInterface(ClassWithoutInterface $class): void
    {
    }

    public function methodWithBuiltInTypes(string $str, int $num): void
    {
    }
}
