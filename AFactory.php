<?php
/********
    Abstract Factory es un patrón de diseño creacional que resuelve el problema de 
    crear familias enteras de productos sin especificar sus clases concretas.
    El patrón Abstract Factory define una interfaz para crear todos los productos, 
    pero deja la propia creación de productos para las clases de fábrica concretas. 
    Cada tipo de fábrica se corresponde con cierta variedad de producto.
    El código cliente invoca los métodos de creación de un objeto de fábrica en lugar 
    de crear los productos directamente con una llamada al constructor (operador new). 
    Como una fábrica se corresponde con una única variante de producto, todos sus 
    productos serán compatibles.
    El código cliente trabaja con fábricas y productos únicamente a través de sus 
    interfaces abstractas. Esto permite al mismo código cliente trabajar con productos 
    diferentes. Simplemente, creas una nueva clase fábrica concreta y la pasas al 
    código cliente.
 */

namespace RefactoringGuru\AbstractFactory\Conceptual;

/**
  * La interfaz de Abstract Factory declara un conjunto de métodos que devuelven
  * diferentes productos abstractos. Estos productos se denominan familia y son
  * relacionados por un tema o concepto de alto nivel. Los productos de una familia suelen ser
  * capaces de colaborar entre ellos. Una familia de productos puede tener varios
  * variantes, pero los productos de una variante son incompatibles con los productos de
  * otro.
 */
interface AbstractFactory
{
    public function createProductA(): AbstractProductA;

    public function createProductB(): AbstractProductB;
}

/**
  * Las Fábricas de Concreto producen una familia de productos que pertenecen a un solo
  * variante. La fábrica garantiza que los productos resultantes son compatibles. Nota
  * que las firmas de los métodos de Concrete Factory devuelven un producto abstracto,
  * mientras que dentro del método se instancia un producto concreto.
 */
class ConcreteFactory1 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA1();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB1();
    }
}

/**
 * Cada Concrete Factory tiene una variante de producto correspondiente.
 */
class ConcreteFactory2 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA2();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB2();
    }
}

/**
  * Cada producto distinto de una familia de productos debe tener una interfaz base. Todos
  * las variantes del producto deben implementar esta interfaz.
 */
interface AbstractProductA
{
    public function usefulFunctionA(): string;
}

/**
 * Los Productos de Concreto son creados por las Fábricas de Concreto correspondientes.
 */
class ConcreteProductA1 implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "The result of the product A1.";
    }
}

class ConcreteProductA2 implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "The result of the product A2.";
    }
}

/**
  * Aquí está la interfaz base de otro producto. Todos los productos pueden interactuar
  * entre sí, pero la interacción adecuada solo es posible entre productos de
  * la misma variante concreta.
 */
interface AbstractProductB
{
    /**
     * Product B is able to do its own thing...
     */
    public function usefulFunctionB(): string;

    /**
      * ...pero también puede colaborar con el ProductoA.
      *
      * The Abstract Factory se asegura de que todos los productos que crea sean del
      * misma variante y por lo tanto, compatible.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string;
}

/**
 * Los Productos de Concreto son creados por las Fábricas de Concreto correspondientes.
 */
class ConcreteProductB1 implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "The result of the product B1.";
    }

    /**
     * La variante, Producto B1, solo puede funcionar correctamente con la variante,
      * Producto A1. Sin embargo, acepta cualquier instancia de AbstractProductA como
      * un argumento.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B1 collaborating with the ({$result})";
    }
}

class ConcreteProductB2 implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "The result of the product B2.";
    }

    /**
 * La variante, Producto B2, solo puede funcionar correctamente con la variante,
      * Producto A2. Sin embargo, acepta cualquier instancia de AbstractProductA como
      * un argumento.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B2 collaborating with the ({$result})";
    }
}

/**
* El código de cliente funciona con fábricas y productos solo a través de resumen
  * tipos: AbstractFactory y AbstractProduct. Esto le permite pasar cualquier fábrica o
  * subclase de producto al código del cliente sin romperlo.
  */
function clientCode(AbstractFactory $factory)
{
    $productA = $factory->createProductA();
    $productB = $factory->createProductB();

    echo $productB->usefulFunctionB() . "\n";
    echo $productB->anotherUsefulFunctionB($productA) . "\n";
}

/**
 * El código del cliente puede funcionar con cualquier clase de fábrica concreta.
 */
echo "Client: Testing client code with the first factory type:\n";
clientCode(new ConcreteFactory1());

echo "\n";

echo "Client: Testing the same client code with the second factory type:\n";
clientCode(new ConcreteFactory2());