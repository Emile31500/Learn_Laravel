<html>
    <head>
    </head>
    <title>

    </title>
    <body>
        <form action="{{ route('sign.post') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email : </label>
                <input value="emile00013@gmail.com" type="email" name="email"id="email"/>
            </div>
            <div>
                <label for="name">Nom : </label>
                <input value="emile" type="text" name="name" id="name"/>
            </div>
            <div>
                <label for="password">Mot de passe : </label>
                <input value="emile" type="password" name="password" id="password"/>
            </div>
            <div>
                <label for="confPassword">Mot de passe : </label>
                <input value="emile" type="password" name="confPassword" id="confPassword"/>
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

            @if (session('info'))
                <div class="alert alert-success">
                    <ul>
                        {{ session('info') }}
                    </ul>
                </div>
            @endif
        </form>
    </body>
</html>