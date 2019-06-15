@if(app()->environment('production'))
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141728791-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-141728791-1');
    </script>
@endif
