<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules\Fixtures;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;

final class NoConfigResolverParametersInConstructorFixture
{
    private ConfigResolverInterface $configResolver;

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;

        $configResolver->hasParameter('foo');
        $configResolver->getParameter('foo');
    }

    public function foo(): void
    {
        //this is allowed outside of constructor - no error reported by PHPStan
        $this->configResolver->hasParameter('bar');
    }
}
