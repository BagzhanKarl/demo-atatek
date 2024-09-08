<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="mb-3">
                            <select id="start" class="form-control">
                                <option value="1">Ұлы жүз</option>
                                <option value="2">Орта жүз</option>
                                <option value="3">Кіші жүз</option>
                                <option value="4">Жүзден тыс</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="name" placeholder="Есімі">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" id="search">Іздеу</button>
                        </div>
                    </div>
                    <div id="searchContainer"></div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    $(document).ready(function(){
        $('#search').on('click', function (){
            let start = $('#start').val()
            let name = $('#name').val()

            $.ajax({
                url: '../php/api/search.php',
                method: 'POST',
                data: {
                    start: start,
                    name: name,
                },
                success: function(data){
                    $('#searchContainer').html(data);
                }
            })
        })
    })
</script>
</body>
</html>
