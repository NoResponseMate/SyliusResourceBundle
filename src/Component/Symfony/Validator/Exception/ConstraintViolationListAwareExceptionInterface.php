<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Symfony\Validator\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * An exception which has a constraint violation list.
 */
interface ConstraintViolationListAwareExceptionInterface
{
    /**
     * Gets constraint violations related to this exception.
     */
    public function getConstraintViolationList(): ConstraintViolationListInterface;
}
