<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testWalletInitialization(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    public function testAddFunds(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);
        $this->assertEquals(100.0, $wallet->getBalance());
    }

    public function testRemoveFunds(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100.0);

        $wallet->removeFund(50.0);
        $this->assertSame(50.0, $wallet->getBalance());
    }

    public function testSetBalance(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(50.0);
        $this->assertEquals(50.0, $wallet->getBalance());
    }

    public function testSetCurrency(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }

    public function testInvalidBalance(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->setBalance(-50.0);
    }

    public function testInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('GBP');
    }

    public function testInsufficientRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->removeFund(50.0);
    }

    public function testNegativeRemoveFunds(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->removeFund(-50.0);
    }

    public function testInvalidAddFunds(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->addFund(-50.0);
    }
}
