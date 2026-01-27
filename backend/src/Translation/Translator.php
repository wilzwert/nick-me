<?php

namespace App\Translation;

use App\Exception\ErrorMessage;
use App\Exception\ValidationErrorMessage;

/**
 * @author Wilhelm Zwertvaegher
 */
class Translator implements TranslatorInterface
{
    /**
     * @var array<string, string>
     */
    private static array $messages = [
        ErrorMessage::WORD_ALREADY_EXISTS => 'Le mot existe déjà',
        ErrorMessage::WORD_NOT_FOUND => 'Le mot n\'a pas été trouvé',
        ErrorMessage::NICK_NOT_FOUND => 'Le pseudo est introuvable',
        ErrorMessage::NO_SUBJECT_FOUND => 'Aucun sujet trouvé',
        ErrorMessage::NO_QUALIFIER_FOUND => 'Aucun qualificatif trouvé',
        ErrorMessage::NOTIFICATION_NOT_FOUND => 'Notification introuvable',
        ErrorMessage::VALIDATION_FAILED => 'Saisie invalide',
        ValidationErrorMessage::UNKNOWN_ERROR => 'Erreur inconnue',
        ValidationErrorMessage::INVALID_EMAIL => 'Email incorrect',
        ValidationErrorMessage::INVALID_FIELD_VALUE => 'Valeur incorrecte',
        ValidationErrorMessage::FIELD_CANNOT_BE_NULL => 'La valeur doit être renseignée',
        ValidationErrorMessage::FIELD_CANNOT_BE_EMPTY => 'La valeur ne peut pas être vide',
        ValidationErrorMessage::MALFORMED_FIELD_VALUE => 'Valeur incorrecte',
        ValidationErrorMessage::FIELD_CANNOT_CONTAIN_HTML => 'Valeur incorrecte',
        ValidationErrorMessage::ENTITY_ALREADY_EXISTS => 'La ressource existe déjà',
        ValidationErrorMessage::FIELD_VALUE_TOO_SHORT => 'La valeur est trop courte',
        ValidationErrorMessage::QUALIFIER_POSITION_CANNOT_BE_EMPTY => 'La position du qualifier doit être renseignée',
    ];

    public function translate(string $message): string
    {
        return self::$messages[$message] ?? $message;
    }
}
