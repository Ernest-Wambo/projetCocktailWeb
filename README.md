# projetCocktailWeb
> This project is a web base projet managing the list of alcoholic drinks owned by a restaurant
- it is composed of a basic login system, a user favorite management system (php no JS used),
- the user info and data are being saved into cookies and sessions, later deported to .php files, no database used
- search bar permitting the search of specific cocktails from a list of items liked or disliked with the following syntax
  > Une fonctionnalité devra permettre aux utilisateurs de rechercher des recettes à partir d’un ensemble d’aliments qu’il 
souhaite utiliser (e.g. « jus de fruit » et « sel ») et d’un ensemble d’aliments qu’il ne souhaite pas utiliser (e.g. « pas de 
whisky »).  
La syntaxe imposée pour la requête utilise les signes + (ou vide) pour indiquer un aliment qu’on veut, - pour indiquer 
un aliment qu’on ne veut pas et " (double-quote) pour marquer le début et la fin d’un aliment composé de plusieurs 
mots. Par exemple : "Jus de fruits" +Sel -Whisky serait une requête possible pour la recherche donnée en 
exemple précédemment. L’interface doit comporter une seule zone de saisie pour saisir la requête. Les doubles-quotes 
et l’espace sont considérés comme les seuls séparateurs d’aliments à prendre en compte ; un aliment est donc une suite 
de caractères sans espace, excepté s’il est entre double-quotes. Pour un bon usage des signes + et –, un espace est requis 
avant. La recherche doit tenir compte de la hiérarchie des aliments. Par exemple, une recette contenant du jus de tomate 
satisfait la requête « jus de fruits ». La requête saisie par l’utilisateur doit restée affichée dans la zone de saisie. 
L’affichage des résultats se fera sous la zone de navigation (la partie de gauche comprenant la navigation, cf. illustration 
de l’interface ci-après, n’apparaitra pas). L’affichage comportera 2 parties :  - la 1ère partie doit afficher le résultat du traitement de la requête sous la forme de la liste des aliments reconnus 
(égalité stricte avec les aliments de la hiérarchie) et éventuellement les parties de la requête non reconnus, si il 
y en a. Par exemple, pour la requête ci-dessus, l’application indiquera à l’utilisateur :  
Liste des aliments souhaités : Jus de fruits, Sel 
Liste des aliments non souhaités : Whisky 
Pour la requête "Jus de legumes" Citron grenadine et saucisson l’application indiquera : 
Liste des aliments souhaités : Citron 
Éléments non reconnus dans la requête : Jus de legumes, grenadine, et, saucisson 
car il manque un accent dans « Jus de legumes », il manque une majuscule à « grenadine », et les mots simples     
« et » et « saucisson » ne sont pas, non plus, des aliments présents dans la hiérarchie des aliments.  
Enfin, un message d’erreur doit être affiché si la requête contient un nombre impair de double-quotes ("). 
Par exemple, pour la requête "Jus de fruits +Citron, l’application indiquera simplement : 
Problème de syntaxe dans votre requête : nombre impair de double-quotes 
Autre exemple de requête qui illustre un potentiel problèmes concernant les séparateurs d’aliments  : 
Pour Vodka +Sel-Citron l’application indiquera : 
Liste des aliments souhaités : Vodka 
Éléments non reconnus dans la requête : Sel-Citron 
car il manque un espace avant le signe – ; la chaine « Sel-Citron » est donc considérée comme étant un alim - la 2nde partie affichera les recettes correspondant aux aliments souhaités et non souhaités qui auront été 
reconnus dans la phase d’analyse de la requête. Deux cas sont à distinguer : 
o 1) Si au moins une des listes «  aliments souhaités » ou « aliments non souhaités » n’est pas vide, il 
est possible de traiter la demande. La recherche à implémenter doit être une recherche exacte si un 
seul aliment est recherché (souhaité ou pas) et une recherche approximative s’il y a au moins 2 
aliments (souhaitées ou pas). En haut de page, s’affichera le nombre de recettes qui satisfont 
entièrement la recherche. De plus, dans le cas d’une recherche approximative, on affichera 
également le nombre de recettes qui satisfont partiellement la recherche. Dans tous les cas, on 
affiche ensuite les cocktails résultant de la recherche. Dans le cas d’une recherche approximative, 
les recettes seront classées par ordre décroissant de satisfaction. Le score de satisfaction d’une 
recette est le nombre de critères reconnus de la requête (aliments souhaités ou pas) satisfaits par la 
recette au regard du nombre de critères dans la requête (un score de 100% indique une recette qui 
satisfait entièrement les critères reconnus). Ce score doit être affiché pour chaque cocktail présenté 
en résultat. Seul les recettes dont le score est supérieur à 0 doivent être affichées. 
o 2) Si les deux listes «  aliments souhaités » et « aliments non souhaités » sont  vides (dans les cas 
où aucun aliment n’a été reconnu), indiquer simplement : simplement : 
Problème dans votre requête : recherche impossible
- respecting the constraints imposed by our employer (teacher) had a score of 20/20 for this project
