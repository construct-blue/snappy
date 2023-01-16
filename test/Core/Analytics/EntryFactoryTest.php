<?php

declare(strict_types=1);

namespace BlueTest\Core\Analytics;


use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Blue\Core\Analytics\EntryFactory;
use Blue\Core\Http\Attribute;
use Blue\Core\Http\Header;
use Blue\Core\Http\Method;
use PHPUnit\Framework\TestCase;

class EntryFactoryTest extends TestCase
{
    public function testCreateEmptyRequest()
    {
        $request = new ServerRequest();
        $request = $request->withAttribute(Attribute::REQUEST_ID->value, '123');
        $request = $request->withAttribute(Attribute::REQUEST_TIMESTAMP->value, 123);
        $entry = (new EntryFactory())->create($request, null);
        $this->assertEquals('123', $entry->getRequestId());
        $this->assertEquals(123, $entry->getTimestamp());
    }

    public function testCreate()
    {
        $serverParams = [];
        $uri = new Uri('/path?id=123#main');
        $method = Method::GET->name;
        $headers = [
            Header::USER_AGENT->value => 'test agent',
            Header::ACCEPT_LANGUAGE->value => 'de-AT',
            Header::HOST->value => 'example.com',
            Header::REFERER->value => 'referrer.com',
        ];
        $cookieParams = [];
        $queryParams = [
            'id' => 123
        ];
        $request = new ServerRequest(
            $serverParams,
            [],
            $uri,
            $method,
            'php://input',
            $headers,
            $cookieParams,
            $queryParams
        );
        $request = $request->withAttribute(Attribute::REQUEST_ID->value, '123');
        $request = $request->withAttribute(Attribute::REQUEST_TIMESTAMP->value, 123);
        $entry = (new EntryFactory())->create($request, null);
        $this->assertNotEmpty($entry->getRequestId());
        $this->assertNotEmpty($entry->getTimestamp());
        $this->assertEquals('de', $entry->getHeaderLanguage());
        $this->assertEquals('AT', $entry->getHeaderRegion());
        $this->assertEquals('/path', $entry->getPath());
        $this->assertEquals('example.com', $entry->getHost());
        $this->assertEquals('test agent', $entry->getUserAgent());
        $this->assertEquals(0, $entry->getStatusCode());
        $this->assertEquals('', $entry->getReasonPhrase());
        $this->assertEquals('referrer.com', $entry->getReferrer());
    }
}
