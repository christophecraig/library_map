# Dessin d'un plan de bibliothèque/centre de documentation pour PMB

## `Important`

Le plan doit être au format **SVG**, il peut être réalisé avec n'importe quel logiciel de dessin vectoriel tel que [InkScape](https://inkscape.org/release/inkscape-0.92.4/) ou encore [Adobe Illustrator](https://www.adobe.com/fr/products/illustrator.html).

## Grouper les formes

Vous devez impérativement grouper dans une balise `<g>` tous les éléments SVG qui appartiennent à une même division de votre bibliothèque dans votre PMB. Par exemple : Toutes les étagères physiques (rayonnages) de votre bibliothèque qui correspondent à une même section doivent se trouver à l'intérieur du même groupe (balise `<g type="section">`).

Si votre groupe de formes se rapporte à une section ou à une localisation, il est recommandé de créer en tant que premier enfant de ce groupe une forme géométrique le représentant. Cette forme, en plus de permettre une visibilité accrue de la zone sur le plan, permettra d'interagir plus facilement avec le plan (actions au clic, survol...). Si vous n'appliquez pas cette consigne, vous pourrez toujours associer le groupe parent depuis un sélecteur.

## Balises

`<rect>`, `<circle>`, `<polygon>`, `<ellipse>`, `<path>`

Toute balise autre que les 5 ci-dessus sera considérée comme sans intérêt par le logiciel PMB dans le cadre de la localisation d'un exemplaire. Elle ne sera pas retirée du plan, mais les attributs que vous pourriez placer dessus ne seront pas interprêtés par PMB.

Il est à noter que vous pouvez tout à fait utiliser d'autres éléments SVG qui ne sont pas à proprement parler des formes comme du texte avec la balise `<text>` ou un lien avec la balise `<a>`.

Nous vous encourageons aussi à représenter sur le plan des élements n'ayant pas d'intérêt pour PMB comme des flèches, des toilettes, l'accueil, un coin informatique ou n'importe quel point de repère facilement visible par un visiteur. Ces formes ne seront pas interprétées par PMB mais faciliteront grandement la tâche des lecteurs pour se repérer au sein de votre structure.

## Attributs autorisés

### Sur les balises `<g>`

Chaque groupe SVG `<g>` doit contenir un seul et unique élément SVG enfant avec un attribut type identique. En effet, il sera en quelque sorte la représentation visuelle de son parent. Il peut contenir autant d'autres balises que vous le désirez, tant qu'elles ne disposent pas du même attribut type.

+ **type** *(requis)* : Prendra l'une des 3 valeurs suivantes : "*section*", "*location*", "*shelves*", ou "*group*"  
Pour les bibliothèques disposant le plusieurs étages au sein d'une même localisation, on utilisera `<g type="group">` comme conteneur. Cela permettra de limiter le contenu affiché à l'étage et non pas à toute la localisation.  
Pour les étagères physiques, qu'il y en ait une ou plusieurs, on utilisera `<g type="shelves">` comme conteneur. Ce sont ces groupes qui seront associés lorsqu'on aura besoin de situer un exemplaire de manière plus précise qu'avec sa localisation et sa section.
+ **id** *(facultatif)*: id de la section ou localisation à renseigner selon la valeur de l'attribut type.
+ **name** *(facultatif)*: Nom de la section, localisation ou du groupe. Il vous sera proposé de le saisir dans PMB pour un groupe. Dans le cas d'une section ou d'une localisation, le nom de celle-ci dans votre PMB sera repris.

### Sur les balises `<rect>, <circle>, <polygon>, <ellipse>, <path>`

+ **type** : Si le type n'est pas renseigné, la forme n'aura pas de sens dans PMB. Elle sera affichée sur le plan mais seulement pour aider le client à se repérer une fois à l'intérieur de la bibliothèque. Aucune interaction avec ne sera possible.