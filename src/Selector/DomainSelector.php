<?php

namespace Superruzafa\Settings\Selector;

use Superruzafa\Settings\Selector;
use Superruzafa\Settings\SelectorException;

class DomainSelector implements Selector
{
    /** @var string */
    private $domain;

    /** @var string[] */
    private $parsedDomain;

    /**
     * Creates a new DomainSelector
     *
     * @param   string  $domain Domain or IPv4
     * @throws  SelectorException
     */
    public function __construct($domain)
    {
        if ($this->isIpv4Format($domain)) {
            if (!$this->validateIpv4($domain)) {
                throw new SelectorException("'$domain' is an invalid IPv4");
            }
        } else {
            if (false === $this->validateDomain($domain)) {
                throw new SelectorException("'$domain' is an invalid domain");
            }
            $this->parsedDomain = $this->splitDomain($domain);
        }

        $this->domain = $domain;
    }

    /**
     * Gets the selector's domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /** @inheritdoc */
    public function select($metadata)
    {
        if ($this->isIpv4Format($metadata)) {
            return $this->doSelectIpv4($metadata);
        }

        if (false !== $this->validateDomain($metadata)) {
            return $this->doSelectDomain($metadata);
        }

        return false;
    }

    /**
     * Does the selection using a domain
     *
     * @param   string  $domain
     * @return  bool
     */
    private function doSelectDomain($domain)
    {
        $parts = $this->splitDomain($domain);
        $result = $this->compareDomains($this->parsedDomain, $parts);
        return false !== $result && $result <= 0;
    }

    private function splitDomain($domain) {
        return array_reverse(explode('.', $domain));
    }

    /**
     * Compares two domains.
     *
     * Returns
     *     -1   if the first domain is more generic than the second one
     *      0   if both domains are the same
     *     +1   if the second domain is more generic than the first one
     *  false   if the domains are incompatible
     *
     * Examples:
     *
     *      | domain1          | domain2              | result |
     *      +------------------+----------------------+--------+
     *      | web1.example.com | ftp.web1.example.com | -1     |
     *      | example.com      | example.com          |  0     |
     *      | www.example.com  | example.com          | +1     |
     *      | www.example.com  | example.net          | false  |
     *      | www.example.com  | ftp.example.com      | false  |
     *
     * @param   string[]    $domain1
     * @param   string[]    $domain2
     * @return  int|false
     */
    private function compareDomains(array $domain1, array $domain2)
    {
        $part1 = array_shift($domain1);
        $part2 = array_shift($domain2);

        while ($part1 && $part2 && $part1 == $part2) {
            $part1 = array_shift($domain1);
            $part2 = array_shift($domain2);
        }

        if (is_null($part1) && is_null($part2)) {
            return 0;
        } elseif (is_null($part1)) {
            return -1;
        } elseif (is_null($part2)) {
            return +1;
        }
        return false;
    }


    /**
     * Checks that an string is a valid RFC 882 domain name
     *
     * @param   string  $domain
     * @return  bool
     * @link http://stackoverflow.com/a/4694816/548825
     */
    private function validateDomain($domain)
    {
        if (!preg_match('/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i', $domain)) {
            return false;
        }

        $domainLength = strlen($domain);
        if (0 == $domainLength || 253 < $domainLength) {
            return false;
        }

        if (!preg_match('/^[^\.]{1,63}(\.[^\.]{1,63})*$/', $domain)) {
            return false;
        }

        return $domain;
    }

    /**
     * Does the selection using an IPv4 string
     *
     * @param   string  $ipv4
     * @return  bool
     */
    private function doSelectIpv4($ipv4)
    {
        if (!$this->validateIpv4($ipv4)) {
            return false;
        }

        return $ipv4 == $this->domain;
    }

    /**
     * Checks that a string has an X.X.X.X format
     *
     * @param   string  $ipv4
     * @return  bool
     */
    private function isIpv4Format($ipv4)
    {
        return 1 == preg_match('/^\d{1,3}(\.\d{1,3}){3}/', $ipv4);
    }

    /**
     * Checks that an string is a valid IPv4.
     *
     * @param   string  $ipv4
     * @return  bool
     */
    private function validateIpv4($ipv4)
    {
        return false !== filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
}
