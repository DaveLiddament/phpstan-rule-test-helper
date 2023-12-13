<?php

goto foo; // ERROR foo

foo:
echo 'foo';
goto bar; // ERROR bar

bar:
echo 'bar';
