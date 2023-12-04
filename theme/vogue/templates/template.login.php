<?php 
// This template becomes part of the Router component
Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('Header');
Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('Footer');
?>

<template xcomponent="@AppRouter">
    <div xif="state=='loading'">
        <!-- Apply your Loading animation here -->
    </div>
    <div xif="state=='active'">
        <section xcomponent="@Header"></section>
        <section xcomponent="@Footer"></section>
    </div>
    <div xif="state=='error'">
        <!-- Apply your Error Page here -->
    </div>
</template>