<?php
namespace PioneerDynamics\AddressAutocomplete\Contracts;

interface AddressAutoCompletionProvider extends AddressAutoCompletionEngine
{
    public function getSuggestions($address): array;
    
    public function mapSuggestions($suggestions): void;

    public function getAddressFromCoordinates($latitude, $longitude): array;
}