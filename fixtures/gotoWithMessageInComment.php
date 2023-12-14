<?php

goto foo; // ERROR goto statement is not allowed. Label: foo

foo:
echo 'foo';
goto bar; // ERROR goto statement is not allowed. Label: bar

bar:
echo 'bar';
