<?php

use Maatwebsite\Sidebar\Append;
use Maatwebsite\Sidebar\Domain\DefaultAppend;
use Mockery as m;

class DefaultAppendTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @var DefaultAppend
     */
    protected $append;

    protected function setUp()
    {
        $this->container = m::mock('Illuminate\Contracts\Container\Container');
        $this->append    = new DefaultAppend($this->container);
    }

    public function test_can_instantiate_new_append()
    {
        $append = new DefaultAppend($this->container);

        $this->assertInstanceOf('Maatwebsite\Sidebar\Append', $append);
    }

    public function test_can_have_custom_appends()
    {
        $append = new StubAppend($this->container);

        $this->assertInstanceOf('Maatwebsite\Sidebar\Append', $append);
    }

    public function test_can_set_name()
    {
        $this->append->setName('name');
        $this->assertEquals('name', $this->append->getName());
    }

    public function test_can_set_url()
    {
        $this->append->setUrl('url');
        $this->assertEquals('url', $this->append->getUrl());
    }

    public function test_can_set_icon()
    {
        $this->append->setIcon('icon');
        $this->assertEquals('icon', $this->append->getIcon());
    }

    public function test_append_can_be_cached()
    {
        $this->append->setName('name');
        $this->append->setUrl('url');
        $this->append->setIcon('icon');

        $serialized   = serialize($this->append);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf('Maatwebsite\Sidebar\Append', $unserialized);
        $this->assertEquals('name', $unserialized->getName());
        $this->assertEquals('url', $unserialized->getUrl());
        $this->assertEquals('icon', $unserialized->getIcon());
    }
}

class StubAppend extends DefaultAppend implements Append
{
}
