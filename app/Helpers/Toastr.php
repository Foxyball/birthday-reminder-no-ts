<?php

namespace App\Helpers;

class Toastr
{
    /**
     * Flash a success toast.
     */
    public static function success(string $message, string $title = 'Success', array $options = []): void
    {
        static::flash('success', $message, $title, $options);
    }

    /**
     * Flash an error toast.
     */
    public static function error(string $message, string $title = 'Error', array $options = []): void
    {
        static::flash('error', $message, $title, $options);
    }

    /**
     * Flash an info toast.
     */
    public static function info(string $message, string $title = 'Info', array $options = []): void
    {
        static::flash('info', $message, $title, $options);
    }

    /**
     * Flash a warning toast.
     */
    public static function warning(string $message, string $title = 'Warning', array $options = []): void
    {
        static::flash('warning', $message, $title, $options);
    }

    /**
     * Flash a toast with a "View more" action button.
     *
     * @param  string  $type  success|error|info|warning
     */
    public static function withButton(string $type, string $message, string $buttonUrl, string $buttonText = 'View more', string $title = '', array $options = []): void
    {
        static::flash($type, $message, $title ?: ucfirst($type), array_merge($options, [
            'showButton' => true,
            'buttonText' => $buttonText,
            'buttonUrl' => $buttonUrl,
        ]));
    }

    /**
     * Base flash method — stores as session flash (forgotten after next request).
     *
     * @param  string  $type  success|error|info|warning
     * @param  array<string,mixed>  $options  showButton, buttonText, buttonUrl
     */
    public static function flash(string $type, string $message, string $title = '', array $options = []): void
    {
        session()->flash('toast', array_merge([
            'type' => $type,
            'title' => $title ?: ucfirst($type),
            'message' => $message,
            'showButton' => false,
            'buttonText' => 'View more',
            'buttonUrl' => null,
        ], $options));
    }
}
