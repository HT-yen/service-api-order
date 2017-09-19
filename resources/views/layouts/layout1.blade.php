<html>
    <head>
        <title>App Name - @yield('title')</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    </head>
    <body>
    <h1 class="header_title">this is content define in layout2 but view first</h1>
        @section('sidebar')
            This is the master sidebar.
        @show

        <div style="color: red">
            @yield('content')
        </div>
    </body>
</html>