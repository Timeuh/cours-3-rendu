<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    private Wallet $wallet;

    public function setUp(): void
    {
        $this->wallet = new Wallet('USD');
    }

    // Test du constructeur pour initialiser le portefeuille
    public function testWalletInitialization(): void
    {
        $this->assertEquals(0, $this->wallet->getBalance());
        $this->assertEquals('USD', $this->wallet->getCurrency());
    }

    // Test de la fonction AddFunds pour ajouter des fonds au portefeuille
    public function testAddFunds(): void
    {
        $this->wallet->addFund(100.0);
        $this->assertEquals(100.0, $this->wallet->getBalance());
    }

    // Test de la fonction RemoveFunds pour retirer des fonds du portefeuille
    public function testRemoveFunds(): void
    {
        $this->wallet->addFund(100.0);

        $this->wallet->removeFund(50.0);
        $this->assertSame(50.0, $this->wallet->getBalance());
    }

    // Test de la fonction setBalance pour définir le solde du portefeuille
    public function testSetBalance(): void
    {
        $this->wallet->setBalance(50.0);
        $this->assertEquals(50.0, $this->wallet->getBalance());
    }

    // Test de la fonction setCurrency pour définir la devise du portefeuille
    public function testSetCurrency(): void
    {
        $this->wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $this->wallet->getCurrency());
    }

    // Test d'exception pour la fonction setBalance en cas de solde invalide
    public function testInvalidBalance(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid balance");
        $this->wallet->setBalance(-50.0);
    }

    // Test d'exception pour la fonction setCurrency en cas de devise invalide
    public function testInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid currency");
        $this->wallet->setCurrency('GBP');
    }

    // Test d'exception pour la fonction RemoveFunds en cas de montant invalide
    public function testInsufficientRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $this->wallet->removeFund(-50.0);
    }

    // Test d'exception pour la fonction RemoveFunds en cas de fonds insuffisants
    public function testNegativeRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Insufficient funds");
        $this->wallet->removeFund(50.0);
    }

    // Test d'exception pour la fonction AddFunds en cas de montant invalide
    public function testInvalidAddFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $this->wallet->addFund(-50.0);
    }
}
