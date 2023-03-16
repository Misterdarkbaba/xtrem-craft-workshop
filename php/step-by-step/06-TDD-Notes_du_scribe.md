# TDD - Nous souhaitons pouvoir gérer des opérations entre montants de différentes devises :

1. Nous devons ajouter un nouveau concept métier : un portefeuille (`Portfolio`) et une classe de test (`Portfolio`) pour pouvoir le tester.


2. Nous avons créé une classe Portfolio et sa classe de test TestPortfolio pour tester l'évaluation du portefeuille dans une devise donnée.


3. Nous avons créé une fonction (`test_should_return_portfolio_in_usd_with_eur_and_usd`) dans la classe PortfolioTest pour tester
la fonction evaluate de la classe Portfolio qui doit convertir tout le porfolio en USD.
Ainsi qu'une (`test_should_return_portfolio_in_krw_with_usd_and_krw`) pour tester la conversion du portfolio en KRW.


4. Nous avons discuté de la manière dont nous allons représenter les montants dans le portefeuille, 
et avons décidé d'utiliser un tableau associatif où chaque clé est le code de devise (par exemple "USD" ou "EUR") 
et chaque valeur est le montant dans cette devise.


5. Nous avons également discuté de la manière dont nous allons implémenter la méthode "evaluate" pour évaluer 
le montant total du portefeuille dans une devise donnée. Nous avons décidé qu'il serait nécessaire de passer en argument 
la devise dans laquelle afficher le montant total et une instance de la classe "Bank". 
L'instance de la classe "Bank" sera utilisée pour convertir les montants de devises différentes en montants dans la devise donnée.
Pour le moment, la méthode "evaluate" ne fait rien d'autre que de retourner un montant fixe de 17 USD, 
mais nous allons l'implémenter plus tard.


6. Nous avons complété et testé les méthodes de test, les tests passe au vert avec des données en dur retourner par la méthode evaluate.


7. Nous avons complété la fonction add pour ajouter de l'argent au portfolio et la fonction evaluate 
pour évaluer le montant total du portefeuille dans une devise donnée.


8. Nous avons créé une autre méthode de test (`test_portfolio_empty_should_return_0`) pour tester que la valeur retourner par le portfolio lorqu'il est vide est de zéro.


9. Pour finir nous avons exécuté les tests et tout fonctionne parfaitement sans erreur.