<?php

namespace spec\Remoblaser\LazyArtisan;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class PhpFileReflectorSpec extends ObjectBehavior
{
    protected $testClass =  "namespace testNamespace; class testClass extends testExtend {}";

    function let()
    {
        $this->beConstructedWith($this->testClass);
    }

    function it_parses_a_class_name_from_a_string()
    {
        $this->getCLassName()->shouldReturn('testClass');
    }

    function it_parses_a_namespace_from_a_string()
    {
        $this->getNameSpace()->shouldReturn('testNamespace');
    }

    function it_parses_a_full_class_name_from_a_string()
    {
        $this->getFullClassName()->shouldReturn('testNamespace\testClass');
    }

    function it_parses_a_extended_class_name_from_a_string()
    {
        $this->getExtendedClass()->shouldReturn('testExtend');
    }
}
