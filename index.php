<html>
<head>
    <!--C3.JS CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css" rel="stylesheet" type="text/css">
</head>
<body>

<button class="random">Push random point to centrifuge</button>
<div id="chart"></div>

<!--C3.JS, D3.JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js" type="text/javascript"></script>

<!--SOCKJS, CENTRIFUGO-->
<script src="https://cdn.jsdelivr.net/sockjs/1.0/sockjs.min.js" type="text/javascript"></script>
<script src="https://rawgit.com/centrifugal/centrifuge-js/master/centrifuge.js" type="text/javascript"></script>

<!--JQUERY-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"  type="text/javascript"></script>

<script type="text/javascript">
    var chart;
    // Конфигурируем
    var centrifuge = new Centrifuge({
        url: 'http://vin.tw3ex.ru/connection',
        project: 'development',
        insecure: true
    });
    // Когда центрифуга подключилась,
    centrifuge.on('connect', function() {
        // просто подписываемся на канал,
        var subscription = centrifuge.subscribe('chart', function(message) {
            // ловим точки и добавляем в график,
            chart.flow({
                columns: [
                    ['sample', message.data.point]
                ],
                duration: 100
            });
        });
        // не забывая взять предыдущие. В реальном мире - берем с api.
        subscription.on('ready', function() {
            subscription.history(function(message) {
                var data = message.data.map(function(point){
                    return point.data.point
                });
                data.reverse();
                data.unshift('sample');
                chart = c3.generate({
                    bindto: '#chart',
                    data: {
                        columns: [
                            data
                        ]
                    }
                });
            });
        });
    });
    // Запускаем
    centrifuge.connect();
    // Дергаем сервер, чтобы отправлял в центрифугу сообщения через редис
    $('.random').click(function(){
        $.get('/random.php');
    });
</script>
</body>
</html>

