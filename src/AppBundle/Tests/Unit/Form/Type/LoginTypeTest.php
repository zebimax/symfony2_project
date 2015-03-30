<?php

namespace AppBundle\Tests\Unit\Form\Type;

use AppBundle\Form\Type\LoginType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoginType */
    protected $formType;

    /** @var TranslatorInterface */
    protected $translator;

    protected function setUp()
    {
        $this->translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock();
        $this->formType = new LoginType($this->translator);
    }

    public function testBuildForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->at(0))->method('add')
        ->willReturn($builder)
        ->with(
            'username',
            'text',
            [
                'required' => true,
                'label' => $this->translator->trans('login_form.username'),
                'constraints'   => [
                    new NotBlank(),
                    new Length(
                        ['min' => LoginType::MIN_USERNAME_LENGTH]
                    ),
                ],
            ]
        );

        $builder->expects($this->at(1))->method('add')
        ->willReturn($builder)
        ->with(
            'password',
            'password',
            [
                'required' => true,
                'label' => $this->translator->trans('login_form.password'),
                'constraints'   => [
                    new NotBlank(),
                    new Length(
                        ['min' => LoginType::MIN_PASSWORD_LENGTH]
                    ),
                ],
            ]
        );

        $builder->expects($this->at(2))->method('add')
        ->willReturn($builder)
        ->with(
            'submit',
            'submit',
            [
                'label' => $this->translator->trans('app.button.submit'),
            ]
        );

        $this->formType->buildForm($builder, array());
    }

    public function testGetName()
    {
        $this->assertEquals('app_login', $this->formType->getName());
    }
}
