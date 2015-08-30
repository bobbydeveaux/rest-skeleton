<?php

namespace spec\DVO;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('DVO\Validator');
    }

    function it_should_validate_an_email_address() {
        $this->isValidEmail('bobby@dvomedia.net')
             ->shouldReturn(true);
        $this->isValidEmail('shane@shanechrisbarker.co.uk')
             ->shouldReturn(true);
        $this->isValidEmail('asdfasdfasdf')
             ->shouldReturn(false);
        $this->isValidEmail(' ')
             ->shouldReturn(false);
    }

    function it_should_only_allow_alpha_characters() {
        $this->isValidString('Bobby')
             ->shouldReturn(true);
        $this->isValidString('2x & 9090()9')
             ->shouldReturn(false);
        $this->isValidString('Bobby 123')
             ->shouldReturn(false);
        $this->isValidString('')
             ->shouldReturn(true);

    }


    function it_should_be_numeric(){

        $this->isValidInt(123)
             ->shouldReturn(true);
        $this->isValidInt('shane')
             ->shouldReturn(false);
        $this->isValidInt('')
             ->shouldReturn(false);

    }

    function it_should_be_a_url(){

        $this->isValidUrl('http://www.google.com')
             ->shouldReturn(true);
        $this->isValidUrl('hfdhjkghjkhjkahdgsk')
             ->shouldReturn(false);
        $this->isValidUrl(' ')
             ->shouldReturn(false);
        $this->isValidUrl(12345)
             ->shouldReturn(false);
        $this->isValidUrl('me.com')
             ->shouldReturn(false);
        $this->isValidUrl('')
             ->shouldReturn(false);

    }

    function it_should_be_username() {

        $this->isValidUsername('shane')
            ->shouldReturn(true);
        $this->isValidUsername('h')
             ->shouldReturn(false);
        $this->isValidUsername('shane')
            ->shouldReturn(true);
        $this->isValidUsername('myusernameisverylongandis444ssjjss')
             ->shouldReturn(false);
        $this->isValidUsername('I love Fish <3')
            ->shouldReturn(false);
    }



    function it_should_be_a_phone_number() {

        $this->isValidPhone('+441159556032')
             ->shouldReturn(true);
        $this->isValidPhone('01159556032')
             ->shouldReturn(false);
        $this->isValidPhone('+')
             ->shouldReturn(false);
        $this->isValidPhone('+shaneDDDD')
             ->shouldReturn(false);
        $this->isValidPhone('++++++')
             ->shouldReturn(false);
    }






}
