<?php

goto foo; // ERROR goto statement is not allowed

foo:
echo 'foo';
goto bar; // ERROR goto statement is not allowed

bar:
echo 'bar';
