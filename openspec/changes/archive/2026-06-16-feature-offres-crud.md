# CRUD Offres d'emploi + Auth Breeze

**Date :** 2026-06-16
**Branch :** `feature/offres-crud`
**Commit :** `315249f`

## Changements

### Offre CRUD

- **`app/Models/Offre.php`** — fillable, casts JSON→array, relation `BelongsTo:user`
- **`app/Models/User.php`** — ajout relation `HasMany:offres`
- **`app/Http/Requests/StoreOffreRequest.php`** — validation : titre (string max:255), description (string), competences_requises (array de strings), experience_min (int 0-50)
- **`app/Http/Requests/UpdateOffreRequest.php`** — idem
- **`app/Policies/OffrePolicy.php`** — autorisation par propriétaire (view/create/update/delete)
- **`app/Http/Controllers/OffreController.php`** — CRUD complet : index paginé, create, store, show, edit, update, destroy ; messages flash, autorisation
- **`resources/views/offres/index.blade.php`** — liste paginée avec lien vers création
- **`resources/views/offres/create.blade.php`** — formulaire avec JS dynamique pour compétences
- **`resources/views/offres/edit.blade.php`** — formulaire prérempli
- **`resources/views/offres/show.blade.php`** — détail avec badges compétences
- **`resources/views/layouts/navigation.blade.php`** — lien « Mes offres » ajouté
- **`resources/views/layouts/app.blade.php`** — flash messages ajoutés
- **`routes/web.php`** — `Route::resource('offres', ...)` dans groupe `auth`
- **`routes/auth.php`** — création du fichier de routes auth (login, register, password reset, etc.)

### Auth (Laravel Breeze)

- Installation de `laravel/breeze` (Blade + Tailwind v4 + dark mode + Pest)
- Controllers Auth : AuthenticatedSession, RegisteredUser, PasswordReset, EmailVerification, ConfirmablePassword, Profile
- Vues auth : login, register, forgot-password, reset-password, confirm-password, verify-email
- Dashboard, profil (édition / suppression de compte)
- 25 tests passent (61 assertions)

## Structure

```
app/
  Http/
    Controllers/
      OffreController.php       # CRUD complet
      Auth/                     # Breeze auth controllers
    Requests/
      StoreOffreRequest.php     # Validation création
      UpdateOffreRequest.php    # Validation modification
  Models/
    Offre.php                   # Casts JSON→array, relations
    User.php                    # + HasMany:offres
  Policies/
    OffrePolicy.php             # Propriétaire uniquement
routes/
  web.php                       # Offre resource + auth
  auth.php                      # Routes auth (login, register…)
resources/views/
  offres/                       # index, create, edit, show
  layouts/
    app.blade.php               # + flash messages
    navigation.blade.php        # + lien « Mes offres »
```
