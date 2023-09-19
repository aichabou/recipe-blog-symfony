<?php

namespace App\Service;

use App\Entity\User;

class PasswordVerifier
{
    /**
     * Vérifie si le mot de passe fourni correspond au mot de passe stocké de l'utilisateur.
     *
     * @param User $user     L'utilisateur pour lequel le mot de passe doit être vérifié.
     * @param string $password Le mot de passe à vérifier.
     *
     * @return bool True si le mot de passe est valide, sinon false.
     */
    public function verifyPassword(User $user, string $password): bool
    {
        // Comparez le mot de passe entré par l'utilisateur avec le mot de passe stocké
        // dans votre base de données pour l'utilisateur donné.

        $storedPassword = $user->getPassword(); // Obtenez le mot de passe haché stocké dans la base de données

        // Implémentez la logique de vérification de mot de passe personnalisée ici

        // Par exemple, vous pouvez utiliser password_verify() si vous stockez les mots de passe de manière sécurisée.
        // Notez que la logique exacte dépend de la manière dont vous avez haché et stocké les mots de passe.
        // Voici un exemple basique en supposant que les mots de passe sont hachés avec bcrypt.

        if (password_verify($password, $storedPassword)) {
            return true; // Le mot de passe est valide
        }

        return false; // Le mot de passe est invalide
    }
}
