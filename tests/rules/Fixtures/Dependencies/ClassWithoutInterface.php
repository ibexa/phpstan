<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies;

class ClassWithoutInterface
{
    public function doSomething(): void
    {
        // Implementation
    }
}
