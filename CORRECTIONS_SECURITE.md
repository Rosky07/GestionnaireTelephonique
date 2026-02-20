# 🔒 Corrections de Sécurité Appliquées

## 📋 Résumé des Corrections

### ✅ **1. Sécurisation des Mots de Passe Administrateur** (queries.php)

**Problème** : Les mots de passe étaient stockés en texte brut dans la base de données → **Danger maximal !**

**Solution** : Utilisation de `password_verify()` pour vérifier les mots de passe hachés.

**Explication simple** :

- Avant : `WHERE motdepasse_admin = "monmotdepasse"`
- Après : Vérifier avec `password_verify()` qui utilise des algorithmes sécurisés (bcrypt, argon2)

**⚠️ ACTION REQUISE** :

1. Connectez-vous à votre PhpMyAdmin
2. Exécutez cette requête pour hacher les mots de passe existants :

```sql
UPDATE administrateur
SET motdepasse_admin = '$2y$10$example_hash_here'
WHERE login_admin = 'votre_login';
```

3. OU utilisez ce script PHP pour hacher les mots de passe :

```php
<?php
// Fichier temporaire : hash_admin_password.php
require_once('connexion.php');

$login = 'votre_login';
$password = 'votre_ancien_motdepasse';
$hash = password_hash($password, PASSWORD_BCRYPT);

$req = $bdd->prepare("UPDATE administrateur SET motdepasse_admin = :hash WHERE login_admin = :login");
$req->execute(['hash' => $hash, 'login' => $login]);

echo "Mot de passe hachéisé avec succès !";
?>
```

**Comment fonctionne password_verify** :

```php
// Lors de la connexion :
$motdePasse = "motdepasseSaisi";
$hashStocke = "$2y$10$..."; // Récupéré de la BD

if (password_verify($motdePasse, $hashStocke)) {
    // ✅ Correct
} else {
    // ❌ Incorrect
}
```

---

### ✅ **2. Création Automatique du Répertoire Photos** (queries.php)

**Problème** : Le dossier `/photomembres/` pouvait ne pas exister → Erreur lors de l'upload

**Solution** : Créer le dossier automatiquement s'il n'existe pas

```php
$photoDir = __DIR__ . '/photomembres/';
if (!is_dir($photoDir)) {
    mkdir($photoDir, 0755, true);  // ✅ Crée le dossier
}
```

**Explication** :

- `is_dir()` : Vérifie si le répertoire existe
- `mkdir()` : Crée le répertoire avec permissions 0755
- `true` : Crée les sous-dossiers si besoin

---

### ✅ **3. Amélioration Validation d'Unicité** (queries.php)

**Problème** : La vérification rejetait les inscriptions si **n'importe quel** champ correspondait

```php
WHERE nom_membre = "Jean"
   OR prenom_membre = "Dupont"
   OR email = "jean@mail.com"
```

→ Impossible que deux Jean s'inscrivent !

**Solution** : Vérifier seulement les champs critiques

```php
WHERE email = :email
   OR contact_membre = :contact_membre
   OR (nom_membre = :nom_membre
       AND prenom_membre = :prenom_membre
       AND datenaissance = :datenaissance)
```

**Explication** :

- ✅ **Email** : Unique et identifiant fiable
- ✅ **Téléphone** : Unique et fiable
- ✅ **Nom + Prénom + Date naissance** : Identifie une personne unique

---

### ✅ **4. Suppression en POST (Plus Sécurisée)** (membres.php)

**Problème** : Suppression en GET

```php
<a href="membres.php?supp=123">Supprimer</a>
```

→ Un attaquant peut créer un lien pour supprimer un membre !

**Solution** : Utiliser un formulaire POST

```html
<form method="POST" onsubmit="return confirm('Confirmer ?');">
  <input type="hidden" name="membre_id" value="<?= $membre['id_membre']; ?>" />
  <button type="submit" name="delete_membre" class="btn btn-danger">
    Supprimer
  </button>
</form>
```

**Explication** :

- **GET** = Lisible dans l'URL → Risque
- **POST** = Données dans le corps de la requête → Sécurisé
- **Confirmation** = Évite les erreurs accidentelles

---

### ✅ **5. Vérification de l'Existence du Profil** (profilmembre.php)

**Problème** : Pas de vérification si le membre existe

```php
$voirprofil = $req->fetch();
// Si NULL, le code continue quand même...
```

**Solution** : Rediriger si le profil n'existe pas

```php
if (!$voirprofil) {
    header('Location: membres.php');
    exit;
}
```

---

### ✅ **6. Amélioration Gestion des Dates** (membres.php)

**Problème** : `strtotime()` peut retourner `false` et causer une erreur

```php
<?= date("d-m-Y", strtotime($membre['datenaissance'])); ?>
// Si datenaissance = NULL → False → Erreur
```

**Solution** :

```php
<?= (!empty($membre['datenaissance']) && strtotime($membre['datenaissance']) !== false)
    ? date("d-m-Y", strtotime($membre['datenaissance']))
    : '<span class="text-warning">Non renseignée</span>';
?>
```

---

## 🎯 Prochaines Étapes Recommandées

### Haute Priorité :

1. ✅ Hacher les mots de passe administrateur existants
2. ⚠️ Ajouter **tokens CSRF** pour tous les formulaires
3. ⚠️ Valider et sanitizer TOUS les inputs utilisateur

### Moyen Priorité :

4. Implémenter la validation côté client (HTML5)
5. Ajouter des logs de sécurité (qui supprime quoi, quand)
6. Chiffrer les photos stockées

### Bas Priorité :

7. Ajouter une authentification à 2 facteurs
8. Rate limiting sur la connexion
9. Alertes email sur les suppressions

---

## 📝 Fichiers Modifiés

1. **queries.php**
   - ✅ Hachage des mots de passe
   - ✅ Création automatique du dossier photos
   - ✅ Amélioration validation d'unicité

2. **membres.php**
   - ✅ Suppression en POST
   - ✅ Vérification des dates

3. **profilmembre.php**
   - ✅ Vérification existence du profil

4. **connexion.php**
   - ✅ Chaîne DSN corrigée (déjà bon)

---

## 🧪 Comment Tester

### Test 1 : Connexion Admin

1. Mettez à jour le mot de passe du compte admin
2. Essayez de vous **connecter** → ✅ Doit fonctionner
3. Essayez un mauvais mot de passe → ❌ Doit refuser

### Test 2 : Suppression Membre

1. Allez sur la liste des membres
2. Cliquez sur "Supprimer" → Doit afficher une confirmation
3. **Confirmez** → Le membre doit être supprimé

### Test 3 : Upload Photo

1. Inscrivez un nouveau membre avec une photo
2. Le dossier `/photomembres/` doit être créé automatiquement
3. La photo doit être stockée

---

## ⚠️ Mises en Garde

- **Ne pas** stocker les photos dans un dossier web accessible
- **Ne pas** faire confiance aux extensions de fichier
- **Ne pas** oublier de hacher les mots de passe existants
- **Toujours** valider côté serveur (pas seulement côté client)
