<?php
namespace PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion;

use PioneerDynamics\AddressAutocomplete\Contracts\AddressAutoCompletionProvider;

class Google extends AddressAutoCompletionManager implements AddressAutoCompletionProvider
{
    public function getSuggestions($address): array
    {
        $response = $this->client->get('/autocomplete/json', [
            'input' => $address,
            'key' => config('address-autocompletion.providers.google.config.api_key'),
            'types' => 'address',
        ]);

        return $response->json('predictions');
    }

    public function mapSuggestions($suggestions): void
    {
        foreach ($suggestions as $suggestion) {

            $place_details = $this->getPlaceDetails($suggestion['place_id']);
            
            $this->addAddress(
                latitude: $place_details['geometry']['location']['lat'],
                longitude: $place_details['geometry']['location']['lng'],
                country: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'country')['long_name']),
                country_code: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'country')['short_name']),
                street_number: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'street_number')['long_name']),
                street: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'route')['long_name']),
                city: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'locality')['long_name']),
                state: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'administrative_area_level_1')['long_name']),
                post_code: rescue(fn() => $this->getAddressComponent($place_details['address_components'], 'postal_code')['long_name']),
                formatted_address: $place_details['formatted_address'],
            );
        }
    }

    private function getAddressComponent($components, $type)
    {
        foreach ($components as $component) {
            if (in_array($type, $component['types'])) {
                return $component;
            }
        }
    }

    private function getPlaceDetails($place_id): array
    {
        $response = $this->client->get('/details/json', [
            'place_id' => $place_id,
            'key' => config('address-autocompletion.providers.google.config.api_key'),
        ]);

        return $response->json('result');
    }
}