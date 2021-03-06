<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
use Symfony\Component\Form\FormConfig;

class FormConfigTest extends \PHPUnit_Framework_TestCase
{
    public function getHtml4Ids()
    {
        return array(
            array('a0', true),
            array('a9', true),
            array('z0', true),
            array('A0', true),
            array('A9', true),
            array('Z0', true),
            array('#', false),
            array('a#', false),
            array('a$', false),
            array('a%', false),
            array('a ', false),
            array("a\t", false),
            array("a\n", false),
            array('a-', true),
            array('a_', true),
            array('a:', true),
            // Periods are allowed by the HTML4 spec, but disallowed by us
            // because they break the generated property paths
            array('a.', false),
            // Contrary to the HTML4 spec, we allow names starting with a
            // number, otherwise naming fields by collection indices is not
            // possible.
            // For root forms, leading digits will be stripped from the
            // "id" attribute to produce valid HTML4.
            array('0', true),
            array('9', true),
            // Contrary to the HTML4 spec, we allow names starting with an
            // underscore, since this is already a widely used practice in
            // Symfony2.
            // For root forms, leading underscores will be stripped from the
            // "id" attribute to produce valid HTML4.
            array('_', true),
        );
    }

    /**
     * @dataProvider getHtml4Ids
     */
    public function testNameAcceptsOnlyNamesValidAsIdsInHtml4($name, $accepted)
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        try {
            new FormConfig($name, null, $dispatcher);
            if (!$accepted) {
                $this->fail(sprintf('The value "%s" should not be accepted', $name));
            }
        } catch (\InvalidArgumentException $e) {
            // if the value was not accepted, but should be, rethrow exception
            if ($accepted) {
                throw $e;
            }
        }
    }
}
