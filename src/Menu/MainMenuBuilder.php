<?php


namespace App\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;

class MainMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * @param FactoryInterface $factory
     * @param EntityManagerInterface $em
     */
    public function __construct(FactoryInterface $factory, EntityManagerInterface $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }


    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('home', array(
            'label' => "Home",
            'route' => 'home',
            'extras' => [
                'icon' => 'fas fa-home ',
            ]
        ));


        return $menu;
    }
}