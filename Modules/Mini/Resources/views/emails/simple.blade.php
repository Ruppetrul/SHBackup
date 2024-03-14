<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новый заказ</title>
</head>
<body>

<p>Идентификатор корзины: {{ $orderData['id'] }}</p>
<p>Сумма заказа: {{ $orderData['total'] }}</p>

<h2>Содержимое заказа:</h2>
<ul>
    @foreach ($orderData['lines'] as $line)
        <li>
            <strong>Наименование товара:</strong> {{ $line->title }} <br>
            <strong>Цена:</strong> {{ $line->price }} <br>
            <strong>Количество:</strong> {{ $line->count }} <br><br>
        </li>
    @endforeach
</ul>

<p>Дата создания заказа: {{ \Carbon\Carbon::parse($orderData['created_at'])->format('Y-m-d H:i:s') }}</p>

</body>
</html>
