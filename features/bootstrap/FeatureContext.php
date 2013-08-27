<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;
//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
       // $this->useContext('mink', new MinkContext());
    }
    
    /**
     * Returns the Doctrine entity manager.
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }
    
    /**
     * @Given /^There is no "([^"]*)" in database$/
     */
    public function thereIsNoInDatabase($entityName)
    {
        $entities = $this->getEntityManager()->getRepository('TableRestaurantBundle:'.$entityName)->findAll();
        foreach ($entities as $eachEntity) {
            $this->getEntityManager()->remove($eachEntity);
        }

        $this->getEntityManager()->flush();
    }
    
    /**
     * @When /^I add restaurant "([^"]*)"$/
     */
    public function iAddRestaurant($name)
    {
        $entity = new Table\RestaurantBundle\Entity\Restaurant();
        $entity->setName($name);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @Then /^I should find restaurant "([^"]*)"$/
     */
    public function iShouldFindRestaurant($name)
    {
        $restaurnat = $this->getRepository('TableRestaurantBundle:Restaurant')->findOneByName($name);

        $found = false;
        if ($restaurnat) {
            $found = true;
        }

        assertTrue($found);
    }
}
