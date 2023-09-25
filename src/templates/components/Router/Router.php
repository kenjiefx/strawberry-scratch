<template xcomponent="@Router">
    <div xif="state=='loading'"></div>
    <div xif="state=='active'">
        <?php template_content(); ?>
    </div>
    <div xif="state=='error'"></div>
</template>