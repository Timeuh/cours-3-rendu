<?php

namespace Tests;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    private $peopleTab;

    //setup
    public function setUp(): void
    {
        $this->peopleTab = array(
            new Person('John Doe', 'EUR'),
            new Person('Jane Doe', 'USD'),
            new Person('Philipp Doe', 'USD'),
        );

        $this->peopleTab[0]->getWallet()->addFund(120);
        $this->peopleTab[1]->getWallet()->addFund(20);
    }

    public function testConstructor(): void
    {
        $person = new Person('Jane Foster', 'EUR');
        $this->assertEquals('Jane Foster', $person->getName());
        $this->assertEquals('EUR', $person->getWallet()->getCurrency());
        $this->assertEquals(0, $person->getWallet()->getBalance());
    }

    public function testGetName(): void
    {
        $this->assertEquals('John Doe', $this->peopleTab[0]->getName());
        $this->assertEquals('Jane Doe', $this->peopleTab[1]->getName());
        $this->assertEquals('Philipp Doe', $this->peopleTab[2]->getName());
    }

    public function testSetName(): void
    {
        $this->peopleTab[0]->setName('Jean Rochefort');
        $this->assertEquals('Jean Rochefort', $this->peopleTab[0]->getName());
    }

    public function testGetWallet(): void
    {
        $testWalletEur = new Wallet('EUR');
        $testWalletEur->setBalance(120);
        $testWalletUsd = new Wallet('USD');
        $testWalletUsd->setBalance(20);
        $testWalletUsdBis = new Wallet('USD');

        $this->assertEquals($testWalletEur, $this->peopleTab[0]->getWallet());
        $this->assertEquals($testWalletUsd, $this->peopleTab[1]->getWallet());
        $this->assertEquals($testWalletUsdBis, $this->peopleTab[2]->getWallet());
    }

    public function testSetWallet(): void
    {
        $testWallet = new Wallet('USD');
        $this->peopleTab[0]->setWallet($testWallet);
        $this->assertEquals($testWallet, $this->peopleTab[0]->getWallet());
    }

    public function testHasFund(): void
    {
        $this->assertEquals(false, $this->peopleTab[2]->hasFund());
        $this->assertEquals(true, $this->peopleTab[1]->hasFund());
    }

    public function testTransfertFund()
    {
        $this->peopleTab[1]->transfertFund(10, $this->peopleTab[2]);
        $this->assertEquals(10, $this->peopleTab[2]->getWallet()->getBalance());
        $this->assertEquals(10, $this->peopleTab[1]->getWallet()->getBalance());

        $this->peopleTab[1]->transfertFund(10, $this->peopleTab[1]);
        $this->assertEquals(10, $this->peopleTab[1]->getWallet()->getBalance());

        $this->expectException(\Exception::class);
        $this->peopleTab[0]->transfertFund(10, $this->peopleTab[1]);
    }

    public function testDivideWallet()
    {
        $person = new Person('Titi Doe', 'USD');

        $people = array(
            new Person('Toto Doe', 'USD'),
            new Person('Mama Doe', 'USD'),
            new Person('Nono Doe', 'USD'),
        );

        $person->getWallet()->addFund(30);
        $people[0]->getWallet()->addFund(35.30);
        $people[1]->getWallet()->addFund(12.50);
        $people[2]->getWallet()->addFund(46.38);

        $person->divideWallet($people);
        $this->assertEquals(0, $person->getWallet()->getBalance());
        $this->assertEquals(45.30, $people[0]->getWallet()->getBalance());
        $this->assertEquals(22.50, $people[1]->getWallet()->getBalance());
        $this->assertEquals(56.38, $people[2]->getWallet()->getBalance());
    }

    public function testBuyProduct()
    {
        $product = new Product('Carrot', ['USD' => 16], 'food');

        $this->peopleTab[1]->buyProduct($product);
        $this->assertEquals(4, $this->peopleTab[1]->getWallet()->getBalance());

        $this->expectException(\Exception::class);
        $this->peopleTab[0]->buyProduct($product);

        $this->expectException(\Exception::class);
        $this->peopleTab[1]->buyProduct($product);
    }
}
