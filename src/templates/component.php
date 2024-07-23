<?php 
// Declare here all the child components to be used within this component
# Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('ExampleComponent');
?>

<template plunc-name="COMPONENT_NAME">
    <div xif="state=='loading'"></div>
    <div xif="state=='active'"></div>
    <div xif="state=='error'"></div>
</template>