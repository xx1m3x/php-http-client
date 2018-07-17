<?php
/*
Copyright (c) 2017 Jorge Matricali <jorgematricali@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

namespace Matricali\Http;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

/**
 * @author Gabriel Polverini <gpolverini_ext@amco.mx>
 *
 * @group Client
 */
class ClientTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException Matricali\Http\Client\Exception
     */
    public function testSendRequest()
    {
        $client = new Client();
        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn('http://404.php.net/');
        $request->getMethod()->willReturn(HttpMethod::GET);
        $client->sendRequest($request->reveal());
    }

    /**
     * @test
     */
    public function testBasicGet()
    {
        $client = new Client();
        $response = $client->get('http://www.google.com/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('private', $response->getHeader('Cache-Control'));
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertNotEmpty($response->getBody());
    }

    /**
     * @test
     */
    public function testBasicHead()
    {
        $client = new Client();
        $response = $client->head('http://www.google.com/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('private', $response->getHeader('Cache-Control'));
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertEmpty($response->getBody());
    }

    /**
     * @test
     */
    public function testBasicPost()
    {
        $client = new Client();
        $response = $client->post('http://www.google.com/', 'test=test&a=b');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertNotEmpty($response->getBody());
    }

    /**
     * @test
     */
    public function testBasicPut()
    {
        $client = new Client();
        $response = $client->put('http://www.google.com/', 'test=test&a=b');
        $this->assertEquals(411, $response->getStatusCode());
        $this->assertEquals('1.0', $response->getProtocolVersion());
        $this->assertNotEmpty($response->getBody());
    }

    /**
     * @test
     */
    public function testBasicDelete()
    {
        $client = new Client();
        $response = $client->delete('http://www.google.com/', 'test=test&a=b');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertNotEmpty($response->getBody());
    }

    /**
     * @test
     */
    public function testBasicPatch()
    {
        $client = new Client();
        $response = $client->patch('http://www.google.com/', 'test=test&a=b');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertNotEmpty($response->getBody());
    }

    /**
     * @test
     */
    public function testClone()
    {
        $client = new Client();
        $clientCloned = clone $client;
        $this->assertEquals($client, $clientCloned);
    }

    /**
     * @test
     */
    public function testParseHeaderEmpty()
    {
        $client = new Client();
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseHeaders');
        $method->setAccessible(true);

        $this->assertEquals([], $method->invoke($client, ''));
    }

    /**
     * @test
     */
    public function testParseHeaderNotArray()
    {
        $client = new Client();
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseHeaders');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($client, 123));
    }
}
