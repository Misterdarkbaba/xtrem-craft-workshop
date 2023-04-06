### Story 1: Define Pivot Currency

> Que se passe-t-il si je veux convertir une monnaie en une autre ?

#### All exchange rates are defined from the pivot currency

Exemple:
```gherkin
Given a bank with EUR as pivot currency
When I define an exchange rate of 1.2 to USD
Then I can convert 10 EUR to 12 USD
```

Exemple:
```gherkin
Given a bank with EUR as pivot currency
When I define an exchange rate of 1.2 to USD
Then I can convert 12 USD to 10 EUR
```

### Story 2: Add an exchange rate

#### Le résultat a une marge de +/- 1% par rapport au montant initial (Round-tripping)

Exemple:
```gherkin
Given a bank with EUR as pivot currency
And an exchange rate of 0.00073 to KRW
When I convert 20 KRW to EUR
And the result to KRW
Then I have 20 - 1% <= result <= 20 + 1%
```


### Story 3: Convert a Money

> Que se passe-t-il si nous voulons convertir dans une devise ?

#### Cannot convert if exchange rate doesn't exist

Exemple:
```gherkin
Given a bank with EUR as Pivot Currency
When I convert 10 EUR to KRW
Then I receive an error explaining that the system has no exchange rate
```

#### Can convert money on the same money without exchange rate

Exemple:
```gherkin
Given a bank with EUR as Pivot Currency
When I convert 10 EUR to EUR
Then I receive 10 EUR
```

#### Can convert money on another money if exchange rate exists

Exemple:
```gherkin
Given a bank with EUR as Pivot Currency
When I convert 10 EUR to USD
And I have an exchange rate of 1.2 to USD
Then I receive 12 USD
```

### Add an exchange rate

>

