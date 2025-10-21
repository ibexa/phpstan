<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures;

use Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\AbstractClass;
use Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ClassWithoutInterface;
use Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ConcreteClass;
use Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ConcreteClassExtendingAbstract;
use Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\TestInterface;

final class RequireAbstractionInDependenciesFixture
{
    private ConcreteClass $concreteClass;

    private TestInterface $testInterface;

    private ClassWithoutInterface $classWithoutInterface;

    private AbstractClass $abstractClass;

    private ConcreteClassExtendingAbstract $concreteExtendingAbstract;

    public function __construct(
        ConcreteClass $concreteClass,
        TestInterface $testInterface,
        ClassWithoutInterface $classWithoutInterface,
        AbstractClass $abstractClass,
        ConcreteClassExtendingAbstract $concreteExtendingAbstract
    ) {
        $this->concreteClass = $concreteClass;
        $this->testInterface = $testInterface;
        $this->classWithoutInterface = $classWithoutInterface;
        $this->abstractClass = $abstractClass;
        $this->concreteExtendingAbstract = $concreteExtendingAbstract;
    }

    public function methodWithConcreteClass(ConcreteClass $class): void
    {
    }

    public function methodWithInterface(TestInterface $interface): void
    {
    }

    public function methodWithAbstractClass(AbstractClass $abstract): void
    {
    }

    public function methodWithConcreteExtendingAbstract(ConcreteClassExtendingAbstract $concreteExtendingAbstract): void
    {
    }

    public function methodWithoutInterface(ClassWithoutInterface $class): void
    {
    }

    public function methodWithBuiltInTypes(string $str, int $num): void
    {
    }
}
