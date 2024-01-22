<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    // Test du constructeur pour initialiser le portefeuille
    public function testWalletInitialization(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    // Test de la fonction AddFunds pour ajouter des fonds au portefeuille
    public function testAddFunds(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);
        $this->assertEquals(100.0, $wallet->getBalance());
    }

    // Test de la fonction RemoveFunds pour retirer des fonds du portefeuille
    public function testRemoveFunds(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);

        $wallet->removeFund(50.0);
        $this->assertSame(50.0, $wallet->getBalance());
    }

    // Test de la fonction setBalance pour définir le solde du portefeuille
    public function testSetBalance(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(50.0);
        $this->assertEquals(50.0, $wallet->getBalance());
    }

    // Test de la fonction setCurrency pour définir la devise du portefeuille
    public function testSetCurrency(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }

    // Test d'exception pour la fonction setBalance en cas de solde invalide
    public function testInvalidBalance(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid balance");
        $wallet = new Wallet('USD');
        $wallet->setBalance(-50.0);
    }

    // Test d'exception pour la fonction setCurrency en cas de devise invalide
    public function testInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid currency");
        $wallet = new Wallet('GBP');
    }

    // Test d'exception pour la fonction RemoveFunds en cas de montant invalide
    public function testInsufficientRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $wallet = new Wallet('USD');
        $wallet->removeFund(-50.0);
    }

    // Test d'exception pour la fonction RemoveFunds en cas de fonds insuffisants
    public function testNegativeRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Insufficient funds");
        $wallet = new Wallet('USD');
        $wallet->removeFund(50.0);
    }

    // Test d'exception pour la fonction AddFunds en cas de montant invalide
    public function testInvalidAddFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $wallet = new Wallet('USD');
        $wallet->addFund(-50.0);
    }
}
