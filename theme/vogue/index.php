<html>
    <head>
        <title><?php page_title(); ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/kenjiefx/plunc/dist/plunc.0.7.22.min.js"></script>
        <script type="text/javascript">
            window.deployment = { name: '<?php echo page_data('buildMode') ?? 'staging' ?>' }
            const blockAutoSubmit=e=>e.preventDefault();
        </script>
        <?php template_assets(); ?>
    </head>
    <body class="width-24">
        <app plunc-app="app" class="width-24"></app>
        <template plunc-name="app">
            <main plunc-component="AppRouter" class="width-24"></main>
        </template>
        <?php Kenjiefx\StrawberryScratch\Components::register('AppRouter'); ?>
        <?php Kenjiefx\StrawberryScratch\Components::export(); ?>
    </body>
</html>