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

namespace App\Tests\Controller;

use App\Kernel;
use App\Subscription\Foundry\Factory\SubscriptionFactory;
use App\Subscription\Foundry\Story\DefaultSubscriptionsStory;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

final class SubscriptionXmlApiTest extends XmlApiTestCase
{
    use Factories;

    #[Test]
    public function it_allows_showing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::new()
            ->withEmail('marty.mcfly@bttf.com')
            ->create()
        ;

        $this->client->request('GET', '/ajax/subscriptions/' . $subscription->getId());
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'subscriptions/show_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_indexing_subscriptions(): void
    {
        DefaultSubscriptionsStory::load();

        $this->client->request('GET', '/ajax/subscriptions');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'subscriptions/index_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_creating_a_subscription(): void
    {
        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email>marty.mcfly@bttf.com</email>
</root>
EOT;

        $this->client->request(method: 'POST', uri: '/ajax/subscriptions', server: self::$headersWithContentType, content: $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'subscriptions/create_response', Response::HTTP_CREATED);
    }

    #[Test]
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email></email>
</root>
EOT;

        $this->client->request(method: 'POST', uri: '/ajax/subscriptions', server: self::$headersWithContentType, content: $data);

        $file = Kernel::VERSION_ID >= 60400 ? 'subscriptions/create_validation' : 'subscriptions/create_validation_legacy';

        $this->assertResponse($this->client->getResponse(), $file, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_allows_updating_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email>calvin.klein@bttf.com</email>
</root>
EOT;

        $this->client->request(method: 'PUT', uri: '/ajax/subscriptions/' . $subscription->getId(), server: self::$headersWithContentType, content: $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email></email>
</root>
EOT;

        $this->client->request(method: 'PUT', uri: '/ajax/subscriptions/' . $subscription->getId(), server: self::$headersWithContentType, content: $data);

        $file = Kernel::VERSION_ID >= 60400 ? 'subscriptions/update_validation' : 'subscriptions/update_validation_legacy';

        $this->assertResponse($this->client->getResponse(), $file, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_allows_removing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $this->client->request('DELETE', '/ajax/subscriptions/' . $subscription->getId());
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
