<template plunc-name="AppRouter">
    <div plunc-if="state=='loading'">
        <!-- Apply loading screen here -->
    </div>
    <div plunc-if="state=='active'">
    
        <?php template_content(); ?>
    </div>
    <div plunc-if="state=='error'">
        <!-- Apply error page here -->
    </div>
</template>