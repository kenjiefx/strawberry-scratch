<?php 
// This template becomes part of the Router component
Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('Header');
Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('Footer');
Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::register('Profile');
?>

<template xcomponent="@AppRouter">
    <div xif="state=='loading'">
        <!-- Apply your Loading animation here -->
    </div>
    <div xif="state=='active'">
        <button xclick="TestHelper.exec()">Execute From Helper</button>
        <div xblock="/BlockManager/TestActionBlocks/">
            <div xif="BlockManager.TestActionBlocks.state=='empty'">
                Empty test action block state
            </div>
            <div xif="BlockManager.TestActionBlocks.state=='active'">
                Active test action block state 
                <button xclick="BlockManager.TestActionBlocks.showModal()">Show Modal</button>
                <dialog xblock="/ModalManager/TestModal/">
                    <section xblock="/BlockManager/BlockWithinModal/">
                        <div xif="BlockManager.BlockWithinModal.state=='active'">
                            Block within modal has been activated
                        </div>
                    </section>
                    <button xclick="ModalManager.TestModal.close()">Close Modal</button>
                </dialog>
            </div>
        </div>
        <section xcomponent="@Header"></section>
        <section xcomponent="@Header"></section>
        <section xcomponent="@Footer"></section>
        <br>
        <section xcomponent="@Profile"></section>
    </div>
    <div xif="state=='error'">
        <!-- Apply your Error Page here -->
    </div>
</template>