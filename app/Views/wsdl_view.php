<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WSDL Response</title>
</head>
<body>
    <h1>WSDL Response</h1>
    <?php if (!empty($data)): ?>
    <table>
        <thead>
            <tr>
                <th>Value</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
                <tr>
                    <td><?= esc($item['Value']) ?></td>
                    <td><?= esc($item['Description']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No data available.</p>
<?php endif; ?>

</body>
</html>
