<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures;

interface WrongName
{
}

interface CorrectNameInterface
{
}

trait SimpleThing
{
}

trait CorrectNameTrait
{
}

abstract class SimpleClass
{
}

abstract class AbstractCorrectClass
{
}
