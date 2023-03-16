# TDD - Nous souhaitons pouvoir gérer des opérations entre montants de différentes devises :

1. Nous devons ajouter un nouveau concept métier : un portefeuille (`Portfolio`) et une classe de test (`Portfolio`) pour pouvoir le tester.


2. Nous avons créé une classe TestPortfolio qui possède une méthode "test_should" pour tester l'évaluation du portefeuille dans une devise donnée.


3. Nous avons créé une classe Portfolio puis créer les méthodes add pour ajouter des montants à un portefeuille et evaluate pour retourner la somme totale de l'argent du portefeuille puisque nous utilisons ces méthodes dans la méthode "test_should".


4. Nous avons discuté de la manière dont nous allons représenter les montants dans le portefeuille, et avons décidé d'utiliser un tableau associatif où chaque clé est le code de devise (par exemple "USD" ou "EUR") et chaque valeur est le montant dans cette devise.


5. Nous avons également discuté de la manière dont nous allons implémenter la méthode "evaluate" pour évaluer le montant total du portefeuille dans une devise donnée. Nous avons décidé qu'il serait nécessaire de passer en argument la devise dans laquelle afficher le montant total et une instance de la classe "Bank". L'instance de la classe "Bank" sera utilisée pour convertir les montants de devises différentes en montants dans la devise donnée.
   Pour le moment, la méthode "evaluate" ne fait rien d'autre que de retourner un montant fixe de 17 USD, mais nous allons l'implémenter plus tard.


6. Nous avons créé une méthode "getPortfolio" pour récupérer la liste des montants puisque la variable $portfolio qui contient le portfolio est privée


7. Nous avons modifié la méthode "test_should" pour tester seulement l'ajout dans le portfolio des différents montants dans des devises différentes.


8. Nous avons déplacé le test permettant d'évaluer le portefeuille dans une nouvelle méthode de test appelé pour l'instant "test_should2". Cette méthode n'est pas encore finie.