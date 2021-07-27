<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HyperfTest\Di;

use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSource;
use HyperfTest\Di\Stub\Bar;
use HyperfTest\Di\Stub\Container\ContainerProxy;
use HyperfTest\Di\Stub\Foo;
use HyperfTest\Di\Stub\FooInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ContainerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testHas()
    {
        $container = new Container(new DefinitionSource([]));
        $this->assertFalse($container->has(FooInterface::class));
        $this->assertFalse($container->has(NotExistClass::class));
        $this->assertTrue($container->has(Foo::class));
    }

    public function testSet()
    {
        $container = new Container(new DefinitionSource([]));
        $subject = new Foo();
        $container->set(FooInterface::class, $subject);
        $this->assertSame($subject, $container->get(FooInterface::class));
    }

    public function testDefine()
    {
        $container = new Container(new DefinitionSource([]));
        $container->define(FooInterface::class, Foo::class);
        $this->assertInstanceOf(Foo::class, $container->make(FooInterface::class));

        $container->define(FooInterface::class, function () {
            return Mockery::mock(Bar::class);
        });
        $this->assertInstanceOf(Bar::class, $foo = $container->make(FooInterface::class));
    }

    public function testPsrContainer()
    {
        $this->assertInstanceOf(Container::class, new ContainerProxy());
    }
}
