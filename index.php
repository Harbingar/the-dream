<?php
$resultText = ''; // Variable pour stocker le résultat à afficher

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $amount = $_POST['amount'];
    $fromCurrency = $_POST['from_currency'];
    $toCurrency = $_POST['to_currency'];

    // Faire une requête à l'API pour obtenir les taux de change
    $api_key = '6a531c39dc284064ef103a24'; // Remplace avec ta clé API
    $api_url = "https://api.exchangerate-api.com/v4/latest/$fromCurrency";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Après avoir obtenu les données de l'API...
    if ($response !== false) {
        $data = json_decode($response, true);

        // Vérifier si la clé de la devise de destination existe dans les taux retournés
        if (isset($data['rates'][$toCurrency])) {
            $exchangeRate = $data['rates'][$toCurrency];
            $result = $amount * $exchangeRate;

            // Création du texte à afficher dans le paragraphe
            $resultText = number_format($result, 2);
        } else {
            $resultText = 'La devise de destination n\'est pas disponible dans les taux de change fournis par l\'API';
        }
    } else {
        $resultText = 'Erreur de connexion à l\'API';
    }

} else {
    // Si aucun formulaire n'a été soumis, afficher un contenu par défaut
    $resultText = 'Convert here';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertisseur de Monnaie</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kdam+Thmor+Pro&display=swap" rel="stylesheet">
</head>
<body>
    <form method="post">
        <div class="from">
            <input type="text" id="amount" name="amount" class="amount" placeholder="Type here" required>
            <select id="from_currency" name="from_currency" class="selector" required>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
            </select>
        </div>
        
        <div class="to">
            <select id="to_currency" name="to_currency" class="selector" required>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
            </select>
            <div class="result">
                <p class="amount"><?php echo $resultText; ?></p>
            </div>
            
        </div>
        
        <button type="submit" name="convert" class="convert">Convertir</button>
    </form>
</body>
</html>
