<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    //Test constructeur
    public function testWalletInitialization(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    //Test fonction AddFunds
    public function testAddFunds(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);
        $this->assertEquals(100.0, $wallet->getBalance());
    }

    //Test fonction RemoveFunds
    public function testRemoveFunds(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);

        $wallet->removeFund(50.0);
        $this->assertSame(50.0, $wallet->getBalance());
    }

    //Test fonction setBalance
    public function testSetBalance(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(50.0);
        $this->assertEquals(50.0, $wallet->getBalance());
    }

    //Test fonction setCurrency
    public function testSetCurrency(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }

    //Test fonction exception setBalance
    public function testInvalidBalance(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid balance");
        $wallet = new Wallet('USD');
        $wallet->setBalance(-50.0);
    }

    //Test fonction exception setCurrency
    public function testInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid currency");
        $wallet = new Wallet('GBP');
    }

    //Test fonction exception RemoveFunds
    public function testInsufficientRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $wallet = new Wallet('USD');
        $wallet->removeFund(-50.0);
    }

    //Test fonction exception RemoveFunds
    public function testNegativeRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Insufficient funds");
        $wallet = new Wallet('USD');
        $wallet->removeFund(50.0);
    }

    //Test fonction exception AddFunds
    public function testInvalidAddFunds(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $wallet = new Wallet('USD');
        $wallet->addFund(-50.0);
    }
}
