# Dessin d'un plan de bibliothèque/centre de documentation pour PMB

## `Important`

Le plan doit être au format **SVG**, il peut être réalisé avec n'importe quel logiciel de dessin vectoriel tel que [InkScape](https://inkscape.org/release/inkscape-0.92.4/) ou encore [Adobe Illustrator](https://www.adobe.com/fr/products/illustrator.html).

## Grouper les formes

Vous devez impérativement grouper dans une balise `<g>` tous les éléments SVG qui appartiennent à une même division de votre bibliothèque dans votre PMB. Par exemple : Toutes les étagères physiques (rayonnages) de votre bibliothèque qui correspondent à une même section doivent se trouver à l'intérieur du même groupe (balise `<g type="section">`).

## Attributs autorisés

### Sur les balises `<g>`

+ **type** : Prendra l'une des 3 valeurs suivantes : "*section*", "*location*", ou "*group*"
+ **id** : id de la section ou localisation à renseigner selon la valeur de l'attribut type. Sinon, il s'agit de l'id.
+ **name** : Nom de la section, localisation ou du groupe. Facultatif. Il vous sera proposé de le saisir dans PMB pour un groupe. Dans le cas d'une section ou d'une localisation, le nom de celle-ci dans votre PMB sera repris.

### Sur les balises `<rect>`

+ type
+ id
+ name