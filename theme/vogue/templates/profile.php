<?php 
// This template becomes part of the Router component
# Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('ExampleComponent');
?>

<template xcomponent="@AppRouter">
    <div xif="state=='loading'">
        <!-- Apply your Loading animation here -->
    </div>
    <div xif="state=='active'">
    </div>
    <div xif="state=='error'">
        <!-- Apply your Error Page here -->
    </div>
</template>