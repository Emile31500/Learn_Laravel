<html>
    <head>
    </head>
    <title>

    </title>
    <body>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email : </label>
                <input type="email" name="email"id="email"/>
            </div>
            <div>
                <label for="password">Mot de passe : </label>
                <input type="password" name="password" id="password"/>
            </div>
            <input type="submit"/>  
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </body>
</html>