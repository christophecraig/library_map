# Dessin d'un plan de bibliothèque/centre de documentation pour PMB

## `Important`

Le plan doit être au format **SVG**, il peut être réalisé avec n'importe quel logiciel de dessin vectoriel tel que [InkScape](https://inkscape.org/release/inkscape-0.92.4/) ou encore [Adobe Illustrator](https://www.adobe.com/fr/products/illustrator.html).

## Grouper les formes

Vous devez impérativement grouper dans une balise `<g>` tous les éléments SVG qui appartiennent à une même division de votre bibliothèque dans votre PMB. Par exemple : Toutes les étagères physiques (rayonnages) de votre bibliothèque qui correspondent à une même section doivent se trouver à l'intérieur du même groupe (balise `<g type="section">`).

Si votre groupe de formes se rapporte à une section ou à une localisation, il est recommandé de créer en tant que premier enfant de ce groupe une forme géométrique le représentant. Cette forme, en plus de permettre une visibilité accrue de la zone sur le plan, permettra d'interagir plus facilement avec le plan (actions au clic, survol...). Si vous n'appliquez pas cette consigne, vous pourrez toujours associer le groupe parent depuis un sélecteur.

## Balises

`<rect>`, `<circle>`, `<polygon>`, `<ellipse>`, `<path>`

Toute balise autre que les 5 ci-dessus sera considérée comme sans intérêt par le logiciel PMB dans le cadre de la localisation d'un exemplaire. Elle ne sera pas retirée du plan mais simplement

Il est à noter que vous pouvez tout à fait utiliser d'autres éléments SVG qui ne sont pas de véritables formes apparaissant sur le plan tels du texte avec la balise `<text>` ou un lien avec la balise `<a>`. Nous vous encourageons aussi à indiquer sur le plan des élements n'ayant pas d'intérêt pour PMB comme des toilettes, l'accueil ou un coin informatique. Ces formes ne seront pas interprétées par PMB mais faciliteront grandement la tâche des lecteurs pour se repérer au sein de votre structure.

## Attributs autorisés

### Sur les balises `<g>`

+ **type** : Prendra l'une des 3 valeurs suivantes : "*section*", "*location*", ou "*group*"
+ **id** : id de la section ou localisation à renseigner selon la valeur de l'attribut type. Sinon, il s'agit de l'id.
+ **name** : Nom de la section, localisation ou du groupe. Facultatif. Il vous sera proposé de le saisir dans PMB pour un groupe. Dans le cas d'une section ou d'une localisation, le nom de celle-ci dans votre PMB sera repris.

### Sur les balises `<rect>, <circle>, <polygon>, <ellipse>, <path>`

+ **type** : Si le type n'est pas renseigné, aucune interaction directe ne sera permise avec la forme.