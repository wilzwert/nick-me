<?php

namespace App\Exception;

/**
 *  Poor-man error messages translation.
 *  TODO : this should be properly handled by a Translator, but for now it will do.
 *
 * @author Wilhelm Zwertvaegher
 */
class ErrorTranslator
{
    /**
     * @var array<string, string>
     */
    private static array $messages = [
        'error.word_already_exists' => 'Le mot existe déjà',
        'error.word_not_found' => 'Le mot n\'a pas été trouvé',
        'error.nick_not_found' => 'Le pseudo est introuvable',
        'error.no_word_found' => 'Aucun mot trouvé',
        'error.validation_failed' => 'Saisie invalide',
        'error.validation.unknown_error' => 'Erreur inconnue',
        'error.validation.invalid_email' => 'Email incorrect',
        'error.validation.invalid_field_value' => 'Valeur incorrecte',
        'error.validation.field_cannot_be_null' => 'La valeur doit être renseignée',
        'error.validation.field_cannot_be_empty' => 'La valeur ne peut pas être vide',
        'error.validation.malformed_value' => 'Valeur incorrecte',
        'error.validation.field_cannot_contain_html' => 'Valeur incorrecte',
        'error.validation.entity_already_exists' => 'La ressource existe déjà',
        'error.validation.field_value_too_short' => 'La valeur est trop courte',
        'error.validation.qualifier_position_cannot_be_empty' => 'La position du qualifier doit être renseignée',
    ];

    public function translate($message): string
    {
        return self::$messages[$message] ?? $message;
    }
}
