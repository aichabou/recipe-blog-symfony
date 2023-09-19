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
        $storedPassword = $user->getPassword(); // Obtenez le mot de passe haché stocké dans la base de données

        if (password_verify($password, $storedPassword)) {
            return true; // Le mot de passe est valide
        }

        return false; // Le mot de passe est invalide
    }
}
