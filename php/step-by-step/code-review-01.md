~~1. Arborescence MissingExchangeRateException → dans un dossier Exception à part.~~
~~2. Une instance de la classe Currency est appelée dans les méthodes de la classe MoneyCalculator, mais jamais utilisée.~~
~~3. Type de retour manquant sur la fonction create de Bank.php~~
~~4. Type sur le $exchangeRates dans la classe Bank.php~~
~~5. Commentaires pour la plupart inutiles~~

### Correction

1. Enlever les commentaires inutiles pour ne pas avoir à les modifier lorsque la méthode change
2. $currency1 et $currency2 : nommage peu explicite (remplacer par fromCurrency / toCurrency par exemple)
3. Fonction convert dans Bank.php → peu compréhensible. Ne pas hésiter à faire des méthodes privées pour les utiliser
   dans des méthodes publiques

### Points importants

1. Commentaires / doc
2. Lisibilité / compréhension du code
