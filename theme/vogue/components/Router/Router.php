<template xcomponent="@Router">
    <div xif="state=='loading'">
        <!-- Apply your Loading animation here -->
    </div>
    <div xif="state=='active'">
        <?php template_content(); ?>
    </div>
    <div xif="state=='error'">
        <!-- Apply your Error Page here -->
    </div>
</template>