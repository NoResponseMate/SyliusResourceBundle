<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[Serializer\ExclusionPolicy(policy: 'ALL')]
#[ORM\Embeddable]
final class Author
{
    #[Serializer\Expose]
    #[Serializer\Type(name: 'string')]
    #[ORM\Column(name: 'first_name', length: 255)]
    private ?string $firstName = null;

    #[Serializer\Expose]
    #[Serializer\Type(name: 'string')]
    #[ORM\Column(name: 'last_name', length: 255)]
    private ?string $lastName = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
