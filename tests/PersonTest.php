<?php

namespace Tests;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    private $peopleTab;

    /**
     * Setup avant chaque test
     *
     * @return void
     * @throws \Exception
     */
    public function setUp(): void
    {
        // crée des personnes
        $this->peopleTab = array(
            new Person('John Doe', 'EUR'),
            new Person('Jane Doe', 'USD'),
            new Person('Philipp Doe', 'USD'),
        );

        // ajuste le montant pour deux personnes
        $this->peopleTab[0]->getWallet()->addFund(120);
        $this->peopleTab[1]->getWallet()->addFund(20);
    }

    /**
     * Teste le constructeur de Person
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $person = new Person('Jane Foster', 'EUR');
        $this->assertEquals('Jane Foster', $person->getName());
        $this->assertEquals('EUR', $person->getWallet()->getCurrency());
        $this->assertEquals(0, $person->getWallet()->getBalance());
    }

    /**
     * Teste le getter de name
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals('John Doe', $this->peopleTab[0]->getName());
        $this->assertEquals('Jane Doe', $this->peopleTab[1]->getName());
        $this->assertEquals('Philipp Doe', $this->peopleTab[2]->getName());
    }

    /**
     * Teste le setter de name
     *
     * @return void
     */
    public function testSetName(): void
    {
        $this->peopleTab[0]->setName('Jean Rochefort');
        $this->assertEquals('Jean Rochefort', $this->peopleTab[0]->getName());
    }

    /**
     * Teste le getter de Wallet
     *
     * @return void
     * @throws \Exception
     */
    public function testGetWallet(): void
    {
        // crée des Wallet
        $testWalletEur = new Wallet('EUR');
        $testWalletUsd = new Wallet('USD');
        $testWalletUsdBis = new Wallet('USD');

        // ajuste leur montant
        $testWalletEur->setBalance(120);
        $testWalletUsd->setBalance(20);

        // compare avec les Wallet possédés par les personnes
        $this->assertEquals($testWalletEur, $this->peopleTab[0]->getWallet());
        $this->assertEquals($testWalletUsd, $this->peopleTab[1]->getWallet());
        $this->assertEquals($testWalletUsdBis, $this->peopleTab[2]->getWallet());
    }

    /**
     * Teste le setter de Wallet
     *
     * @return void
     */
    public function testSetWallet(): void
    {
        $testWallet = new Wallet('USD');
        $this->peopleTab[0]->setWallet($testWallet);
        $this->assertEquals($testWallet, $this->peopleTab[0]->getWallet());
    }

    /**
     * Teste la méthode hasFund
     *
     * @return void
     */
    public function testHasFund(): void
    {
        $this->assertEquals(false, $this->peopleTab[2]->hasFund());
        $this->assertEquals(true, $this->peopleTab[1]->hasFund());
    }

    /**
     * Teste la méthode transfertFund
     *
     * @return void
     * @throws \Exception
     */
    public function testTransfertFund()
    {
        // transfert classique
        $this->peopleTab[1]->transfertFund(10, $this->peopleTab[2]);
        $this->assertEquals(10, $this->peopleTab[2]->getWallet()->getBalance());
        $this->assertEquals(10, $this->peopleTab[1]->getWallet()->getBalance());

        // transfert entre soi-même
        $this->peopleTab[1]->transfertFund(10, $this->peopleTab[1]);
        $this->assertEquals(10, $this->peopleTab[1]->getWallet()->getBalance());

        // transfert sans fonds
        $this->expectException(\Exception::class);
        $this->peopleTab[0]->transfertFund(10, $this->peopleTab[1]);
    }

    /**
     * Teste la méthode divideWallet
     *
     * @return void
     * @throws \Exception
     */
    public function testDivideWallet()
    {
        $person = new Person('Titi Doe', 'USD');

        $people = array(
            new Person('Toto Doe', 'USD'),
            new Person('Mama Doe', 'USD'),
            new Person('Nono Doe', 'USD'),
        );

        // division entre 3 personnes autres que la personne qui divise
        $person->getWallet()->addFund(30);
        $people[0]->getWallet()->addFund(35.30);
        $people[1]->getWallet()->addFund(12.50);
        $people[2]->getWallet()->addFund(46.38);

        // division entre la personne qui divise et d'autres, personne qui divise inclue
        $person->divideWallet($people);
        $this->assertEquals(0, $person->getWallet()->getBalance());
        $this->assertEquals(45.30, $people[0]->getWallet()->getBalance());
        $this->assertEquals(22.50, $people[1]->getWallet()->getBalance());
        $this->assertEquals(56.38, $people[2]->getWallet()->getBalance());
    }

    /**
     * Teste la méthode buyProduct
     *
     * @return void
     * @throws \Exception
     */
    public function testBuyProduct()
    {
        $product = new Product('Carrot', ['USD' => 16], 'food');

        // achète un produit normalement
        $this->peopleTab[1]->buyProduct($product);
        $this->assertEquals(4, $this->peopleTab[1]->getWallet()->getBalance());

        // achète un produit mauvaise currency
        $this->expectException(\Exception::class);
        $this->peopleTab[0]->buyProduct($product);

        // achète un produit pas assez d'argent
        $this->expectException(\Exception::class);
        $this->peopleTab[1]->buyProduct($product);
    }
}
