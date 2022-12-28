# PHPStan rule testing helper

This is a helper library for slight improvement to DX for testing PHPStan rules.
It allows you to write the expected error message in the fixture file. 
Anything after `// ERROR ` is considered the expected error message.
The test classes are simplified as you now specify just the fixture files, and this library will extract the expected error and calculate the correct line number.

You can also use an `ErrorMessageFormatter` to further decouple tests from the actual error message. 
See [ErrorMessageFormatter](#error-formatter) section.

## Example

Test code extends [AbstractRuleTestCase](src/AbstractRuleTestCase.php). 
As with the PHPStan's `RuleTestCase` use the `getRule` method to setup the rule used by the test.
For each test list the fixture file(s) needed by the test, using the `assertIssuesReported` method.

#### Test code:
```php
use DaveLiddament\PhpstanRuleTestingHelper\AbstractRuleTestCase;

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
There are further benefits by using the [ErrorMessageFormatter](#error-formatter) to decouple the error messages from the test code.



NOTE: You can pass in multiple fixture files. E.g.
```php
$this->assertIssuesReported(
    __DIR__ . '/Fixtures/SomeCode.php', 
    __DIR__ . '/Fixtures/SomeCode2.php',
    // And so on...    
 );
```


## Installation

```shell
composer require --dev dave-liddament/phpstan-rule-test-helper
```

## Error Formatter

The chances are when you developing PHPStan rules the error message for violations will change. 
Making any change will require you to update all the related tests.

### Constant string error messages

In the simplest case the error is a message that does provide any context, other than line number. 
E.g. in the example the error is `Can not call method`. No additional information (e.g. who was trying to call the method) is provided.

Create a class that extends `ConstantErrorFormatter` and pass the error message to the constructor.

```php
class CallableFromRuleErrorFormatter extends ConstantStringErrorMessageFormatter
{
    public function __construct()
    {
        parent::__construct('Can not call method');
    }
}
```

The next step is to update the test to tell it to use this formatter.

```php
class CallableFromRuleTest extends AbstractRuleTestCase 
{
   // getRule method omitted for brevity
   // testAllowedCall method omitted for brevity

    protected function getErrorFormatter(): ErrorMessageFormatter
    {
        return new CallableFromRuleErrorFormatter();
    }
}
```

Now if the error message is changed, the text only needs to be updated in one place.

Finally, the fixture can be simplified.
There is no need to specify the error message in the fixture file, we just need to specify where the error is.

Updated fixture:
```php
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR
    }
}
```

### Error messages with context

Good error message will provide context. 
For example, the error message could be improved to give the name of the calling class. 
The calling class is `SomeClass` so let's update the error message to `Can not call method from SomeCode`.

The fixture is updated to include the calling class name after `// ERROR`

```php
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR SomeCode
    }
}
```

The `CallableFromRuleErrorFormatter` is updated. 
Firstly it now extends `ErrorMessageFormatter` instead of `ConstantErrorFormatter`.
An implementation of `getErrorMessage` is added. 
This is passed everything after `\\ ERROR`, with whitespace trimmed from each side, and must return the expected error message.

```php
class CallableFromRuleErrorFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        return 'Can not call method from ' . $errorContext;
    }
}
```

### Error message helper methods

Sometimes the contextual error messages might have 2 or more pieces of information. 
Continuing the example above, the error message could be improved to give the name of the calling class and the method being called.
E.g. `Can not call Item::updateName from SomeCode`.

The fixture is updated to include both `Item::updateName` and `SomeCode` seperated by the `|` character. 

E.g. `// ERROR`

```php
class SomeCode
{
    public function go(): void
    {
        $item = new Item("hello");
        $item->updateName("world");  // ERROR Item::updateName|SomeCode
    }
}
```

Use the `getErrorMessageAsParts` helper method to do this, as shown below:

```php

class CallableFromRuleErrorFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        $parts = $this->getErrorMessageAsParts($errorContext, 2);
        return sprintf('Can not call %s from %s', $parts[0], $parts[1]);
    }
}
```

The signature of `getErrorMessageAsParts` is:

```php
/**
 * @return list<string>
 */
protected function getErrorMessageAsParts(
    string $errorContext, 
    int $expectedNumberOfParts,
    string $separator = '|', 
): array
```

If you use the `getErrorMessageAsParts` and the number of parts is not as expected, the test will error with a message that tells you file and line number of the invalid error.
