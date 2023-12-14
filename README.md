# PHPStan rule testing helper

This library offers a couple of improvements to  PHPStan's [custom rule test harness](https://phpstan.org/developing-extensions/testing#custom-rules).

This library provides [AbstractRuleTestCase](src/AbstractRuleTestCase.php), which extends PHPStan's `RuleTestCase`.

It offers a simpler way to write tests for custom rules. Specifically:

1. No need to specify line numbers in the test code.
2. You can specify the expected error message once.

## Improvement 1: No more line numbers in tests

The minimal test case specifies the Rule being tested and at least one test.
Each test must call the `assertIssuesReported` method, which takes the path of one or more fixture files.


#### Test code:
```php
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;

class CallableFromRuleTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new CallableFromRule($this->createReflectionProvider());
    }

    public function testAllowedCall(): void
    {
        $this->assertIssuesReported(__DIR__ . '/Fixtures/SomeCode.php');
    }
}
```

The fixture file contains the expected error message.
#### Fixture:

```php 
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR Can not call method
    }
}
```

Every line that contains `// ERROR ` is considered an issue that should be picked up by the rule.
The text after `// ERROR` is the expected error message.

With this approach you don't need to work out the line number of the error. 
This is particularly handy when you update the Fixture file, you no longer have to update all the line numbers in the test.


# Improvement 2: Specify the expected error message once

Often you end up writing the same error message for every violation. To get round this use the `getErrorFromatter` method to specify the error message.

#### Test code:
```php
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;

class CallableFromRuleTest extends AbstractRuleTestCase
{
    // `getRule` and `testAllowedCall` methods are as above and are omitted for brevity
    
    protected function getErrorFormatter(): string
    {
        return "Can not call method";
    }
}
```

The fixture file is simplified as there is no need to specify the error message. 
Any lines where an error is expected need to end with `// ERROR`, the expected error message is taken from the `getErrorFormatter` method.

#### Fixture:

```php 
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR
    }
    
    public function go2(): void
    {
        $item = new Item("hello");
        $item->remove();  // ERROR
    }
}
```

The expected error messages would be:

- Line 6: `Can not call method`
- Line 12: `Can not call method`

The benefits of this approach are no duplication of the error message text. 
Any changes to the error message only need to be made in one place in the test case.


### Adding context to error messages

Good error message require context. The context is added to the fixture file after `// ERROR `. Multiple pieces of context can be added by separating them with the `|` character.

#### Test code:
```php
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;

class CallableFromRuleTest extends AbstractRuleTestCase
{
    // `getRule` and `testAllowedCall` methods are as above and are omitted for brevity
    
    protected function getErrorFormatter(): string
    {
        return "Can not call {0} from within class {1}";
    }
}
```


#### Fixture:

```php 
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR Item::updateName|SomeCode
    }
    
    public function go2(): void
    {
        $item = new Item("hello");
        $item->remove();  // ERROR Item::remove|SomeCode
    }
}
```

The expected error messages would be:

- Line 6: `Can not call Item::updateName from within class SomeCode`
- Line 11: `Can not call Item::remove from within class SomeCode`

### More flexible error messages

If you need more flexibility in the error message, you can return an object that implements the `ErrorMessageFormatter` [interface](src/ErrorMessageFormatter.php).

In the example below the message changes depending on the number of parts in the error context. 

NOTE: This is a contrived example, but it shows how you can use `ErrorMessageFormatter` to create more flexible error messages.

```php
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;

class CallableFromRuleTest extends AbstractRuleTestCase
{
    // `getRule` and `testAllowedCall` methods omitted are as above and are for brevity
    
    protected function getErrorFormatter(): ErrorMessageFormatter
    {
        new class() extends ErrorMessageFormatter {
            public function getErrorMessage(string $errorContext): string
            {
                $parts = $this->getErrorMessageAsParts($errorContext);
                $calledFrom = count($parts) === 2 ?  'class '.$parts[1] : 'outside an object';
                
                return sprintf('Can not call %s from %s', $parts[0], $calledFrom);
            }
        };
   }
}
```

#### Fixture:

```php 
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR Item::updateName|SomeCode
    }
}
    
$item = new Item("hello");
$item->remove();  // ERROR Item::remove
```

The expected error messages would be:

- Line 6: `Can not call Item::updateName from class SomeCode`
- Line 11: `Can not call Item::remove from outside an object`



## Installation

```shell
composer require --dev dave-liddament/phpstan-rule-test-helper
```
