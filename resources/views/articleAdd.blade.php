<html>
    <head>
    </head>
    <title>

    </title>
    <body>

        <form action="{{ route('article.add.post') }}" method="POST">
            @csrf
            <p>
                <label for="title">Titre : </label>
                <br>
                <input type="text" name="title"id="title"/>
            </p>
            <p>
                <label for="content">Contenu: </label>
                <br>
                <textarea type="textarea" name="content" id="content"></textarea>
            </p>
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