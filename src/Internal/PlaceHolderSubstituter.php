<?php

namespace DaveLiddament\PhpstanRuleTestHelper\Internal;

/** @internal  */
final class PlaceHolderSubstituter
{
    /**
     * This takes a string with numbered placeholders and returns a string with the placeholders replaced with the values.
     *
     * E.g. substitute('Hello {0}, my name is {1}', ['Dave', 'John']) returns 'Hello Dave, my name is John'.
     *
     * @param list<string> $substitutions
     *
     * @throws ErrorMessageParseException
     */
    public static function substitute(string $template, array $substitutions): string
    {
        $numberOfSubstitutions = count($substitutions);
        $parts = explode('{', $template);
        $partsCount = count($parts);
        $result = $parts[0];

        for ($i = 1; $i < $partsCount; ++$i) {
            $part = $parts[$i];
            $endOfPlaceholder = strpos($part, '}');
            if (false === $endOfPlaceholder) {
                throw new ErrorMessageParseException('No closing { in placeholder');
            }
            $placeholder = substr($part, 0, $endOfPlaceholder);
            if (!is_numeric($placeholder)) {
                throw new ErrorMessageParseException("Invalid placeholder [{$placeholder}]");
            }
            $placeholderIndex = (int) $placeholder;
            if ($placeholderIndex >= $numberOfSubstitutions) {
                throw new ErrorMessageParseException("Invalid placeholder [{$placeholder}]");
            }
            $result .= $substitutions[$placeholderIndex];
            $result .= substr($part, $endOfPlaceholder + 1);
        }

        return $result;
    }
}
