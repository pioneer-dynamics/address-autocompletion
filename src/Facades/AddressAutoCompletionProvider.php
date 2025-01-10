<?php
namespace PioneerDynamics\AddressAutocomplete\Facades;

use PioneerDynamics\AddressAutocomplete\Contracts\AddressAutoCompletionProvider as AddressAutoCompletionProviderContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Contracts\AddressAutoCompletionEngine autocomplete(string $address)
 * @method static \Illuminate\Support\Collection getAddresses()
 */
class AddressAutoCompletionProvider extends Facade
{
    public static function getFacadeAccessor()
    {
        return AddressAutoCompletionProviderContract::class;
    }
}