<?php 
// Declare all the components that are being used by this template here
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