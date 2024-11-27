<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WSDL Response</title>
</head>
<body>
    <h1>WSDL Response</h1>
    <?php if (empty($data)): ?>
    <p>No data available</p>
<?php else: ?>
    <table border="1">
        <tr>
            <th>Value</th>
            <th>Description</th>
        </tr>
        <?php foreach ($data as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['Value']) ?></td>
                <td><?= htmlspecialchars($item['Description']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
