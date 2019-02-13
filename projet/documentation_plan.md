# Dessin d'un plan de biblioth�que/centre de documentation pour PMB

## `Important`

Le plan doit �tre au format **SVG**, il peut �tre r�alis� avec n'importe quel logiciel de dessin vectoriel tel que [InkScape](https://inkscape.org/release/inkscape-0.92.4/) ou encore [Adobe Illustrator](https://www.adobe.com/fr/products/illustrator.html).

## Grouper les formes

Vous devez imp�rativement grouper dans une balise `<g>` tous les �l�ments SVG qui appartiennent � une m�me division de votre biblioth�que dans votre PMB. Par exemple : Toutes les �tag�res physiques (rayonnages) de votre biblioth�que qui correspondent � une m�me section doivent se trouver � l'int�rieur du m�me groupe (balise `<g type="section">`).

## Attributs autoris�s

### Sur les balises `<g>`

+ **type** : Prendra l'une des 3 valeurs suivantes : "*section*", "*location*", ou "*group*"
+ **id** : id de la section ou localisation � renseigner selon la valeur de l'attribut type. Sinon, il s'agit de l'id.
+ **name** : Nom de la section, localisation ou du groupe. Facultatif. Il vous sera propos� de le saisir dans PMB pour un groupe. Dans le cas d'une section ou d'une localisation, le nom de celle-ci dans votre PMB sera repris.

### Sur les balises `<rect>`

+ type
+ id
+ name