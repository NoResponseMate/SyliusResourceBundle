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

namespace App\Foundry\Story;

use App\Foundry\Factory\BookFactory;
use function Zenstruck\Foundry\Persistence\flush_after;
use Zenstruck\Foundry\Story;

final class MoreBooksStory extends Story
{
    public function build(): void
    {
        flush_after(function () {
            foreach (range(1, 22) as $number) {
                BookFactory::new()
                    ->withTitle('Book ' . $number)
                    ->create()
                ;
            }
        });
    }
}
