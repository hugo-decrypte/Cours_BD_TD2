## Cours_BD_TD2

---
### CHERCHARI Sofien & BENA Hugo

---

Exercice MongoShell

1. liste des produits : 
```shell
db["produits"].find()
```

2. compter les produits :
```shell
db["produits"].countDocuments()
```

3. lister les produits en les triant par numero décroissant


4. Le produit de libellé "Margherita"
```shell
db["produits"].find({ libelle: "Margherita"})
```

5. produits de la catégorie "Boissons"


6. liste des produits, afficher categorie, numero, libelle
```shell
db["produits"].find(
  {}, { numero : 1, libelle : 1, categorie : 1} 
) 
```

7. idem avec en plus la taille et le tarif
```shell
db["produits"].find(
  {}, { numero : 1, libelle : 1, categorie : 1, tarifs: 1} // Projection
)
```

8. produits avec un tarif < 8.0


9. produits avec un tarif grande taille < 8.0


10. insérer un nouveau produit
```shell
db["produits"].insertOne(
  {
    numero:11, 
    libelle: "Lorem Ipsum", 
    description: "Lorem Ipsum Dolor", 
    image: "https://www.google.com/url?sa=t&source=web&rct=j&url=https%3A%2F%2Flapetitebette.com%2Frecette%2Fcalzone-air-fryer%2F&ved=0CBUQjRxqFwoTCPCk3eLUsJEDFQAAAAAdAAAAABAj&opi=89978449",
    tarifs: [
      {
      	taille: "Petit",
      	tarif: 5.99
      },
    	{
        taille: "Moyen",
    		tarif: 7.49,
      },
  		{
        taille: "Grand Mangeur",
        tarif: 8.99
      }
    ]
  }
)
```

11. les recettes associées au produit 1 :
```shell
db.produits.aggregate([
  // 1. Filtrer le document Produit par 'numero'
  { $match: { numero: 1 } },
  
  // 2. Joindre les documents de la collection 'recettes'
  {
    $lookup: {
      from: "recettes",      // La collection à joindre (où se trouvent les détails des recettes)
      localField: "recettes",  // Le champ dans la collection 'produits' (le tableau d'ObjectIDs)
      foreignField: "_id",     // Le champ de jointure dans la collection 'recettes' (l'ID de la recette)
      as: "detailsRecettes"  // Le nom du nouveau tableau où les documents recettes seront placés
    }
  },
  
  // 3. Projeter pour afficher uniquement les recettes et exclure l'ID du produit (facultatif)
  {
    $project: {
      _id: 0,
      detailsRecettes: 1
    }
  }
])
```