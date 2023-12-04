<html>
    <head>
        <title><?php page_title(); ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/kenjiefx/strawberry-js/dist/strawberry.0.9.6.min.js"></script>
        <script type="text/javascript">
            window.deployment = {
                name: '<?php echo page_data('buildMode') ?? 'staging' ?>'
            }
        </script>
        <script type="text/javascript">const blockAutoSubmit=e=>e.preventDefault();</script>
        <?php template_assets(); ?>
    </head>
    <body class="width-24">
        <app xstrawberry="app" class="width-24"></app>
        <template xstrawberry="app">
            <section xcomponent="@AppRouter" class="width-24"></section>
        </template>
        <?php template_content(); ?>
        <?php Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry::export(); ?>
    </body>
</html>