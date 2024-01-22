<?php

namespace Tests;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    // Teste la méthode getName() pour s'assurer qu'elle renvoie le nom correct du produit
    public function testGetName(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99], 'food');
        $this->assertEquals('Test Product', $product->getName());
    }

    // Teste la méthode getPrices() pour vérifier qu'elle renvoie les prix corrects du produit
    public function testGetPrices(): void
    {
        $prices = ['USD' => 10.99, 'EUR' => 8.99];
        $product = new Product('Test Product', $prices, 'tech');
        $this->assertEquals($prices, $product->getPrices());
    }

    // Teste la méthode getType() pour s'assurer qu'elle renvoie le type correct du produit
    public function testGetType(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99], 'alcohol');
        $this->assertEquals('alcohol', $product->getType());
    }

    // Teste la méthode setType() avec une valeur valide pour garantir qu'elle modifie correctement le type du produit
    public function testSetTypeValid(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99], 'food');
        $product->setType('tech');
        $this->assertEquals('tech', $product->getType());
    }

    // Teste la méthode setType() avec une valeur invalide pour garantir qu'elle lance une exception
    public function testSetTypeInvalid(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid type');

        $product = new Product('Test Product', ['USD' => 10.99], 'food');
        $product->setType('invalid_type');
    }

    // Teste la méthode setPrices() pour garantir qu'elle affecte correctement les prix du produit
    public function testSetPrices(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99], 'tech');
        $prices = ['USD' => 19.99, 'EUR' => 15.99];
        $product->setPrices($prices);
        $this->assertEquals($prices, $product->getPrices());
    }

    // Teste la méthode getTVA() pour s'assurer qu'elle renvoie le taux de TVA correct en fonction du type du produit
    public function testGetTVA(): void
    {
        $foodProduct = new Product('Test Food', ['USD' => 10.99], 'food');
        $this->assertEquals(0.1, $foodProduct->getTVA());

        $techProduct = new Product('Test Tech', ['USD' => 10.99], 'tech');
        $this->assertEquals(0.2, $techProduct->getTVA());
    }

    // Teste la méthode listCurrencies() pour vérifier qu'elle renvoie correctement la liste des devises du produit
    public function testListCurrencies(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99, 'EUR' => 8.99], 'other');
        $this->assertEquals(['USD', 'EUR'], $product->listCurrencies());
    }

    // Teste la méthode getPrice() avec une devise valide pour garantir qu'elle renvoie le prix correct du produit
    public function testGetPriceValidCurrency(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99, 'EUR' => 8.99], 'other');
        $this->assertEquals(10.99, $product->getPrice('USD'));
    }

    // Teste la méthode getPrice() avec une devise non disponible pour garantir qu'elle lance une exception appropriée
    public function testGetPriceCurrencyNotAvailable(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Currency not available for this product');

        $product = new Product('Test Product', ['USD' => 10.99], 'other');
        $product->getPrice('EUR');
    }

    // Teste la méthode getPrice() avec une devise invalide pour garantir qu'elle lance une exception appropriée
    public function testGetPriceInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');

        $product = new Product('Test Product', ['USD' => 10.99, 'EUR' => 8.99], 'other');
        $product->getPrice('INVALID');
    }

    // Teste la méthode setPrices() avec une devise invalide pour garantir qu'elle gère correctement cette situation
    public function testSetPricesWithInvalidCurrency(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99], 'tech');

        $prices = ['USD' => 19.99, 'INVALID' => 15.99];

        $product->setPrices($prices);

        $this->assertEquals(['USD' => 19.99], $product->getPrices());
    }

    // Teste la méthode setPrices() avec un prix négatif pour garantir qu'elle gère correctement cette situation
    public function testSetPricesWithNegativePrice(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99], 'tech');

        $prices = ['USD' => -5.99, 'EUR' => 15.99];

        $product->setPrices($prices);

        $this->assertEquals(['USD' => 10.99, 'EUR' => 15.99], $product->getPrices());
    }

    // Teste la méthode getPrice() avec une devise valide pour garantir qu'elle renvoie le prix correct du produit
    public function testGetPriceWithValidCurrency(): void
    {
        $product = new Product('Test Product', ['USD' => 10.99, 'EUR' => 8.99], 'other');
        $this->assertEquals(10.99, $product->getPrice('USD'));
    }

    // Teste la méthode getPrice() avec une devise invalide pour garantir qu'elle lance une exception appropriée
    public function testGetPriceWithInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');

        $product = new Product('Test Product', ['USD' => 10.99, 'EUR' => 8.99], 'other');
        $product->getPrice('INVALID');
    }
}
