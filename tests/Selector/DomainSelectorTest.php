<?php

namespace Superruzafa\Settings\Selector;

class DomainSelectorTest extends \PHPUnit_Framework_TestCase
{
    public function invalidDomainProvider()
    {
        return array(
            array('goo gle.com'),
            array('google..com'),
            array('google.com '),
            array('google-.com'),
            array('.google.com'),
            array('<script'),
            array('alert('),
            array('.'),
            array('..'),
            array(' '),
            array('-'),
            array(''),
            array('this-whole.domain-contains.two-hundred.and.fifty-four.characters.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.foo.fo.es'),
            array('this-subdomain-contains-64-characters-xxxxxxxxxxxxxxxxxxxxxxxxxx.org')
        );
    }

    public function validDomainProvider()
    {
        return array(
            array('a'),
            array('0'),
            array('192.168.1'),
            array('a.b'),
            array('localhost'),
            array('google.com'),
            array('news.google.co.uk'),
            array('xn--fsqu00a.xn--0zwm56d'),
        );
    }

    public function invalidIpv4Provider()
    {
        return array(
            array('255.255.255.256'),
            array('123.234.24.343'),
        );
    }

    public function validIpv4Provider()
    {
        return array(
            array('192.168.1.1'),
            array('127.0.255.1'),
            array('0.0.0.0'),
            array('255.255.255.255'),
        );
    }

    /**
     * @test
     * @dataProvider invalidDomainProvider
     */
    public function createWithInvalidDomain($domain)
    {
        $this->setExpectedException('Superruzafa\Settings\SelectorException', 'invalid domain');
        new DomainSelector($domain);
    }

    /**
     * @test
     * @dataProvider invalidIpv4Provider
     */
    public function createWithInvalidIp($ip)
    {
        $this->setExpectedException('Superruzafa\Settings\SelectorException', 'invalid IPv4');
        new DomainSelector($ip);
    }

    /**
     * @test
     * @dataProvider validDomainProvider
     */
    public function createWithValidDomain($domain)
    {
        $selector = new DomainSelector($domain);
        $this->assertEquals($domain, $selector->getDomain());
    }

    /**
     * @test
     * @dataProvider validIpv4Provider
     */
    public function createWithValidIpv4($ipv4)
    {
        $selector = new DomainSelector($ipv4);
        $this->assertEquals($ipv4, $selector->getDomain());
    }

    public function successfulSelectionProvider()
    {
        return array(
            //    Selector's domain     Item's metadata
            array('com',                'com'),
            array('com',                'example.com'),
            array('example.com',        'example.com'),
            array('example.com',        'www.example.com'),
            array('www.example.com',    'www.example.com'),

            array('192.168.1.1',        '192.168.1.1'),
        );
    }

    /**
     * @test
     * @dataProvider successfulSelectionProvider
     */
    public function successfulSelection($selectorDomain, $itemMetadata)
    {
        $selector = new DomainSelector($selectorDomain);
        $this->assertTrue($selector->select($itemMetadata));
    }

    public function failedSelectionProvider()
    {
        return array(
            //    Selector's domain         Item's metadata
            array('example.com',            'com'),
            array('www.example.com',        'example.com'),
            array('web1.www.example.com',   'www.example.com'),

            array('com',                    'org'),
            array('example.com',            'ejemplo.com'),
            array('www.example.com',        'ftp.example.com'),

            array('192.168.1.1',            '127.0.0.1'),
            array('192.168.1.1',            '192.168.1.100'),

            array('example.com',            ''),
            array('example.com',            'a e i o u'),
            array('example.com',            'goo gle com'),
            array('example.com',            '666.333.222.111'),
        );
    }

    /**
     * @test
     * @dataProvider failedSelectionProvider
     */
    public function failedSelection($selectorDomain, $itemMetadata)
    {
        $selector = new DomainSelector($selectorDomain);
        $this->assertFalse($selector->select($itemMetadata));
    }
}
