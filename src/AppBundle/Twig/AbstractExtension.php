<?php

namespace AppBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translatorInterface
     */
    public function __construct(TranslatorInterface $translatorInterface)
    {
        $this->translator = $translatorInterface;
    }
}
