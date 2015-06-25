<?php

class EntityAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testEntityUser()
    {
        $obj = new \DVO\Entity\User;
        $this->assertInstanceOf('\DVO\Entity\EntityAbstract', $obj);
    }

    public function testEntityMagicFuncs()
    {
        $obj = new \DVO\Entity\User;
        $obj->setId('123');
        $obj->setUsername('foo');

        $this->assertEquals('foo', $obj->getUsername());
    }

    /**
     * @group entityTests
     */
    public function testEntityConstruct()
    {
        $obj = new \DVO\Entity\User(array('id' => 123));
        $this->assertEquals(123, $obj->getId());
    }

    public function testGetUserData()
    {
        $obj = new \DVO\Entity\User;
        $obj->setId('123');

        $data = $obj->getData();

        $this->assertEquals(array(
            'id'   => '123',
            'username' => '',
            'password' => ''
        ), $data);
    }

    /**
     * @expectedException \DVO\Entity\Exception
     */
    public function testDefaultMagic()
    {
        $obj = new \DVO\Entity\User;
        $var = $obj->placeholder();
    }

    /**
     * @expectedException \DVO\Entity\Exception
     */
    public function testMagicSetFuncsFail()
    {
        $obj = new \DVO\Entity\User;
        $obj->something = 'test';
        $this->assertNull($obj->something);
    }

    /**
     * @group entityTests
     * @expectedException \DVO\Entity\Exception
     */
    public function testMagicGetFuncsFail()
    {
        $obj = new \DVO\Entity\User;
        $testing = $obj->something;
    }

}