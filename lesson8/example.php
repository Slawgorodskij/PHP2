<?php




function someFunction(bool $isOne = true, int $two = 123): string
{
    return $isOne . $two;
}

$reflection = new ReflectionFunction('someFunction');


echo $reflection->getReturnType()->getName() . "\n";

foreach ($reflection->getParameters() as $parameter) {
    echo $parameter->getName().'['.$parameter->getType()->getName()."]\n".$parameter->getDefaultValue();
}
