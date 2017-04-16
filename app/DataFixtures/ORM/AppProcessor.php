<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/04/17
 * Time: 14:31
 */

namespace DietaBundle\DataFixtures\ORM;

use DietaBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class FriendsProcessor implements ProcessorInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->$container = $container;
    }


    /**
     * Processes an object before it is persisted to DB
     *
     * @param object $object instance to process
     */
    public function preProcess($object)
    {


    }

    /**
     * Processes an object after it is persisted to DB
     *
     * @param object $object instance to process
     */
    public function postProcess($object)
    {
        // TODO: Implement postProcess() method.

        if (!$object instanceof User) {
            return;
        }

        $amount = 10;
        $em = $this->container->get('doctrine.orm.entity_manager');

        //Get the number of rows from your table
        $rows = $em->getRepository('DietaBundle:User')->findTotalAmount();

        $offset = max(0, rand(0, $rows - $amount - 1));

        //Get the first $amount users starting from a random point


        $result = $em->getRepository('DietaBundle:User')->findSomeUsers($object,$amount,$offset);

        foreach ($result as $closefriend){

            $object->addMyFriend($closefriend);


        }

        $em->persist($object);
        $em->flush();
    }
}